# Architecture: SyliusMailerBundle

## Purpose

Symfony bundle that integrates the Sylius Mailer Component with the Symfony framework. Provides Twig rendering adapters, Symfony Mailer transport adapter, DI configuration, and a debug console command.

## Directory Structure

```
src/
  Bundle/
    Sylius_Mailer_Bundle.php
    DependencyInjection/
      Configuration.php                    Config tree (sender address/name, emails map)
      Sylius_Mailer_Extension.php          Wires adapters, email definitions
      Compiler/
        Adapter_Pass.php                   Wires renderer + sender adapter services
        Renderer_Adapter_Pass.php
        Sender_Adapter_Pass.php
    Renderer/Adapter/
      Email_Default_Adapter.php            Default (no-op) renderer
      Email_Twig_Adapter.php               Renders email templates via Twig
    Sender/Adapter/
      Default_Adapter.php                  Logs email (dev/null for tests)
      Symfony_Mailer_Adapter.php           Sends via Symfony Mailer (symfony/mailer)
    Console/Command/
      Debug_Mailer_Command.php             Lists configured emails + sender settings
      Dumper/                              Console output formatters for debug command
    Resources/config/services/            PHP-format service definitions

  Component/                              Standalone component (no Symfony dependency)
    Event/                                Email render + send events
    Factory/Email_Factory.php             Creates Email model instances
    Model/Email.php / Email_Interface.php  Email configuration value object
    Modifier/
      Composite_Email_Modifier.php        Applies multiple EmailModifier decorators in order
      Email_Modifier_Interface.php        Contract for email post-processors
    Provider/
      Default_Settings_Provider.php       Reads global from/name from configuration
      Email_Provider.php                  Loads Email models from the registry
    Renderer/Adapter/                     Abstract adapter + interface for renderers
    Sender/
      Sender.php                          Orchestrates render + send + event dispatch
      Adapter/                            Abstract adapter + Cc-aware interface
    Sylius_Mailer_Events.php              Event name constants
```

## Key Design Decisions

- **Bundle + Component split**: The Component has no Symfony dependency and can be used standalone. The Bundle wires Twig, Symfony Mailer, and the event dispatcher.
- **Modifier chain**: `Composite_Email_Modifier` applies a chain of `Email_Modifier_Interface` decorators before rendering, allowing plugins to alter email subjects, recipients, or content.
- **Event hooks**: `EmailRenderEvent` and `EmailSendEvent` are dispatched around rendering and sending, enabling tracking pixels, BCC injection, etc.
- **Debug command**: `debug:sylius-mailer` lists all configured email codes, templates, and sender settings without sending anything.

## Extension Points

- Tag services `sylius_mailer.renderer_adapter` to replace the Twig renderer.
- Tag services `sylius_mailer.sender_adapter` to replace the Symfony Mailer transport.
- Implement `Email_Modifier_Interface` and tag it to add pre-send email transformations.
- Subscribe to `EmailRenderEvent` / `EmailSendEvent` for cross-cutting concerns.

## Dependency Flow

```
Application -> Sender::send('order_confirmation', ['customer@example.com'], ['order' => $order])
  -> EmailProvider::getEmail('order_confirmation') -> Email model
  -> Composite_Email_Modifier::modify(email, data)
  -> TwigRendererAdapter::render(email, data) -> RenderedEmail
  -> Symfony_Mailer_Adapter::send(recipients, from, renderedEmail, ...)
     -> symfony/mailer -> SMTP / SendGrid / etc.
```
