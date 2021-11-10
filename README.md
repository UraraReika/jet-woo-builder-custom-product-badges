# JetWooBuilder Custom Product Badges

Add meta field to single product page in admin area where user can pick badges.
Then with Elementor page builder separate style them in widgets. This functionality works in Products Grid and Archive Sale Badge Widgets.

When you active plugin in Edit product page you will be able to see [new meta field section](https://i.gyazo.com/2b5a4d7eab3075bb31e546477d3971ef.png).
In this section you can choose some preregistered [badges](https://i.gyazo.com/8dff318fce74bc5506401215191dbd0a.png) and although with the help of filter hook
`'jet-woo-builder-cpb/integration/badges'` you can extend the list of the badges.

Here is example:
```phpt
    add_filter( 'jet-woo-builder-cpb/integration/badges', '__your_theme_prefix_extend_custom_badges_list' );

    function __your_theme_prefix_extend_custom_badges_list( $list ) {
        $list['limited'] = 'Limited';
    
        return $list;
    }
```

You can use this code in your child theme.

Then in widgets you will see this [section](https://i.gyazo.com/eaa85fbbb8874f13e5321e29fafed24a.png).
[Here](https://i.gyazo.com/f2c52bbe240559aed3e6462ed2ab7414.png) you can disable default badge and make some unique colors for other badges. Use [standard style badge sections](https://i.gyazo.com/40ee3f70f642507694643efa618f9931.png) for other styling.

If you want to use this functionality in Products Grid or Products List widget then make sure you enable this [option](https://i.gyazo.com/c7a717ce7f77a44b0a2024b6bbce66f7.png) .
