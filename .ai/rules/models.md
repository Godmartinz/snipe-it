---
paths:
  - 'app/Models/**'
---

# Models

## Models validate themselves with watson/validating
Models carry their own validation: `use Watson\Validating\ValidatingTrait` plus a `protected $rules` array. `$model->save()` returns false when validation fails and `$model->getErrors()` holds the messages.

This is a second layer on top of the Form Request, not a replacement for it.

## Declare a presenter on the model
A model that renders in the UI sets `protected $presenter = \App\Presenters\<Entity>Presenter::class` and `use App\Presenters\Presentable`, exposing `$model->present()`.

## Share model behavior through opt-in traits
Cross-cutting model behavior comes from traits in `app/Models/Traits`, opted into per model: `CompanyableTrait` (FMCS scoping), `Loggable` (action log), `Searchable` (API/datatable search), `Requestable`, `Acceptable`, `HasUploads`.

Add behavior as a trait rather than pushing it into a base class.

## Casts go in the $casts property
Declare casts with `protected $casts = [...]`, not a `casts()` method, even though Laravel 12 supports the method form.

## New-style Attribute accessors need @return Attribute generics for Larastan
If a model attribute has a new-style accessor (`protected function locationId(): Attribute`), Larastan drops the property entirely unless the method has a generic PHPDoc: `/** @return Attribute<TGet, TSet> */` (e.g. `Attribute<int|null, int|null>`). Without it, the DB-column extension defers to the accessor extension, the accessor extension requires strict generics, and the property reads as "undefined" — that is why `@property` tags crept into model docblocks (e.g. PR #19480). Fix by adding the `@return Attribute<...>` generic instead of `@property` tags; plain columns without accessors are inferred from migrations automatically and need no annotation.

TGet is the readable type; **TSet is the writable one — the setter's argument, i.e. what may be assigned**, not what the setter stores. Reserve `never` for attributes genuinely never written: no `set:` closure **and** nothing assigns to them (computed values like `expiresDiffInDays()`). No `set:` closure alone is not enough — an accessor decorating a real column still gets assigned elsewhere, and `never` turns each assignment into `Property Setting::$header_color (never) does not accept mixed`; use `mixed` there. Same reasoning for a setter that narrows: `requestable()` stores `(int) filter_var(...)` but accepts an untyped `$value`, so it is `Attribute<int, mixed>`.
