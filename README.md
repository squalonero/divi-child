# divi-child

To start using Divi Child Theme:

```
cd wp-content/themes
git clone https://github.com/squalonero/divi-child.git
```

1. Then open Wordpress admininistration
2. Navigate to Appereance -> Themes
3. Select Divi Child as Active theme

You're done!

# Creating custom child component based on Divi Theme component

1. Copy file from `www/wp-content/themes/Divi/includes/builder/module` to `www/wp-content/themes/divi-child/includes/builder/module`
2. At the start of the file, replace `require_once 'helpers/Overlay.php';` with `get_template_part( '/includes/builder/module/helpers/Overlay.php' );`
3. Add this property to the class: `public static $shortcode = 'skh_pb_blog';` replace content with your own slug.
4. Inside `init()` method, make value of `$this->slug` equal to $shortcode defined in previous point.
5. Inside `init()` method, change value of `$this->name` and `$this->plural`. Just add the keyword "Child" so we can distinguish the component from the parent one.
6. At the bottom of the file, remove the check `if ( et_builder_should_load_all_module_data() )` till the closing bracket.