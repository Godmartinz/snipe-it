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
