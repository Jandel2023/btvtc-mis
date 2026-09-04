---
name: filament-form-design
description: "Use this skill when asked to design, refactor, or troubleshoot Filament forms in a Laravel app. Covers form layout, field selection, validation, conditional visibility, relationship fields, reusable sections, and save logic for create/edit pages."
license: MIT
metadata:
  author: laravel
---

# Filament Form Design

Use this skill to design and improve Laravel Filament forms so they match the existing application conventions and remain easy to maintain.

## When to Use

- creating or editing a Filament resource form
- designing a create form, edit form, or modal form
- adding conditional fields, relationship selectors, nested repeaters, or grouped sections
- refactoring a hard-to-use form into a clearer layout
- reviewing form validation, save behavior, or field logic

## First Check

Before changing form structure, review the surrounding app patterns:

1. Inspect sibling resource forms and related models for naming and layout conventions.
2. Confirm whether the form is for create, edit, or inline relationship management.
3. Check which fields are required, which are computed, and which are relationship-backed.
4. Match the codebase style before introducing new patterns.

## Design Workflow

1. Identify the record lifecycle and required input.
2. Group fields by intent and separate them with sections, grids, or tabs.
3. Choose the correct field types for the data being captured.
4. Add relationship fields using the project’s existing model conventions.
5. Add reactive logic only where it improves UX, especially conditional visibility and value updates.
6. Validate input and keep save logic explicit and consistent with the resource.
7. Review the final form for clarity, accessibility, and expected behavior.
8. Run the smallest relevant tests, especially Livewire/Pest coverage for Filament forms.

## Field and Layout Guidance

- Use static make() methods for all components.
- Prefer Section and Grid for grouped layouts.
- Use columnSpan() or columnSpanFull() when a field should span multiple columns.
- Keep each form readable; avoid giant single-column or flat layouts when grouping adds clarity.
- Use Repeater for HasMany or inline child records.
- Use Select::make('relation_id')->relationship('relation', 'name') for BelongsTo fields.
- Use live(onBlur: true) on text inputs when changing one field should update another field.

## Conditional Logic

Use Get and Set for field dependencies instead of duplicating logic in multiple places.

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

Select::make('type')
    ->options(['business' => 'Business', 'individual' => 'Individual'])
    ->required()
    ->live(),

TextInput::make('company_name')
    ->required()
    ->visible(fn (Get $get): bool => $get('type') === 'business'),

TextInput::make('title')
    ->required()
    ->live(onBlur: true)
    ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
        'slug',
        Str::slug($state ?? ''),
    )),
```

## Relationship and Data Handling

- Prefer relationship-based selects over custom manual lookup fields.
- Use relationship names consistent with the model definitions.
- Avoid hidden N+1 queries when loading option lists or related data.
- Do not mark fields as dehydrated(false) if they need to be saved.

## Save and Validation Rules

- Validate using the resource’s existing rules and model constraints.
- Keep validation feedback precise and user-friendly.
- Ensure save logic updates the right record without breaking create/edit flow.
- Keep actions and modal behavior aligned with the resource’s established patterns.

## Quality Bar

A form is ready when all of the following are true:

- fields are grouped logically and easy to scan
- labels and required states are clear
- conditional logic feels intentional and not noisy
- relationship fields follow the project conventions
- validation and save behavior are reliable
- the UI is consistent with nearby forms in the app
- relevant tests cover critical validation or save workflows

## Common Pitfalls

- using custom relationship patterns when the project already uses relationship() selectors
- flattening too many fields into one section without structure
- forgetting to add columnSpan() when using multi-column layouts
- using reactive logic that updates too often and makes the form feel sluggish
- adding validation that does not match existing model rules
- making fields hidden from persistence when they should still save

## Example Prompt

Design a Filament create form for the student registration flow. Group personal information in one section, academic details in a second section, and add a conditional field that appears only when the program type is scholarship. Use existing form conventions and keep the layout consistent with other resource forms.
