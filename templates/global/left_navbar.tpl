<div id="sidebar-wrapper" class="">
    <nav id="spy">
        <ul class="sidebar-nav nav">
            <li class="sidebar-brand">
                <a href="/" class="">
                    {block name="Body_Navigation_Logo_Img"}
                        <img src="{$template.logoURL}" alt="{$template.site_name}">
                    {/block}
                </a>
            </li>
            {block name="Body_Navigation_Items"}
                {include file="string:`$template['menuBuilder']`"}
            {/block}

            {block name="Body_Navigation_Language_Selection"}
                {*{include file="language_selection.tpl"}*}
            {/block}
        </ul>
    </nav>
</div>