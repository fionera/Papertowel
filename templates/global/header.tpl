<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>
        {block name="Header_Title_Content"}
            {if $site.siteTitle}
                {$template.site_name} - {$site.siteTitle}
            {else}
                {$template.site_name}
            {/if}
        {/block}
    </title>

    {block name="Header_Include_Stylesheets"}
        {foreach $template.resources.css as $css}
            <link rel="stylesheet" href="{$css}">
        {/foreach}
    {/block}
</head>