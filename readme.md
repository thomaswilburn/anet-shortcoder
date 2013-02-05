ShortCoder
==========

ShortCoder is a WordPress plugin that allows users to easily build new shortcodes from templates. On startup, it reads all the JSON config files in its own directory and parses them to map shortcode tags to templates. Inside those templates, which are just regular PHP files, the shortcode attributes and enclosed text are exposed through the *$attributes* and *$content* variables, respectively (there's no need to recursively call _do_shortcode()_ on the content--ShortCoder will take care of that for you). 

If making shortcodes cleaner and simpler wasn't handy enough, ShortCoder also allows enclosing tags to modify inner tags by specifying another template to override the original definition. So, for example:

JSON:
```json
{
  "br": {
    "template": "br_tag.tmpl"
  },
  "special": {
    "template": "div_tag.tmpl",
    "modifies": {
      "br": "horizontal_rule.tmpl"
    }
  }
}
```

WordPress:
```
[br]
[special]
  [br]
[/special]
[br]
```

Result:
```html
<br>
<div class="special">
  <hr> <!-- br tag was overridden by the "modifies" property -->
</div>
<br> <!-- br tag returns to normal outside of modifying tag -->
```

This is especially handy for building a simple "vocabulary" of shortcodes that expose complex layouts to non-technical WordPress users--tags like image embeds or column breaks can be transparently adapted to their container behind the scenes.

ShortCoder is released under the MIT license and is free for your use.