<x-mail::message>
# Hello from Foodics

## This is an alert message to notify you that one of you ingredients has reached the minimum amount.

- Ingredient name: {{$name}}
- Ingredient current amount: {{$amount}}
- Alert minimum amount: {{$min_amount}}


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
