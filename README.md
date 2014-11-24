markdown-forms
==============

Class to add, validate and mail simple HTML/AJAX forms via Markdown. Currently, basic `<input>` and `<textarea>` elements are supported.

For example, to create a Twitter Bootstrap form:

```html
<form role="form" markdown="1">
  ?{text}("Name" "" "Name..."){.form-control}
  ?{email}("Email" "" "Email..."){.form-control}
  ?{text}("Subject" "" "Subject..."){.form-control}
  ?{textarea}("Message" "" "Message..." 3*20){.form-control}
  ?{submit}("" "Send!"){.form-control}
</form>
```

The basic syntax is as follows:
```
?{type}("label" "value" "placeholder" rows*cols){.class}
```

* **type**: the type of the `<input>` element
* **label**: the label, this also gets converted to an `id` for the `<input>` element
* **value**: the value of the `<input>` element
* **placeholder**: the placeholder for the `<input>` element
* **rows*cols**: number of rows and columns for the `<textarea>` element, ignored for other types
* **class**: the class of the `<input>` element
