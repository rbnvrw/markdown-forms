markdown-forms
==============

Class to add, validate and mail simple HTML/AJAX forms via Markdown. Currently, basic `<input>` and `<textarea>` elements are supported.

## Syntax
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

## Example
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

This gives the following output:
```html
<form role="form">
    <div class="form-group">
        <label for="name">Name</label>
        <input name="name" id="name" placeholder="Name..." class="form-control" type="text">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input name="email" id="email" placeholder="Email..." class="form-control" type="email">
    </div>
    <div class="form-group">
        <label for="subject">Subject</label>
        <input name="subject" id="subject" placeholder="Subject..." class="form-control" type="text">
    </div>
    <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" id="message" placeholder="Message..." class="form-control" rows="3" cols="20"></textarea>
    </div>
    <div class="form-group">
        <input value="Send!" class="form-control" type="submit">
    </div>
</form>
```
The templates for `<input>` and `<textarea>` can be customised by passing them as arguments to the class.
