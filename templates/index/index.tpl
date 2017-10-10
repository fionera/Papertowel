<!DOCTYPE html>
<html lang="{$language.selected}">
{block name="Header"}
    {block name="Include_Header_Template"}
        {include file="../global/header.tpl"}
    {/block}
{/block}

<body>
{block name="Body"}
    {block name="Include_Navbar_Template"}
        {if $template.navbar === "top"}
            {include file="../global/top_navbar.tpl"}
        {elseif $template.navbar === "left"}
            {include file="../global/left_navbar.tpl"}
        {/if}
    {/block}
    <div id="wrapper" class="container">
        <div class="jumbotron">
            {block name="Body_Site_Content"}
            {/block}
        </div>
    </div>
{/block}

{block name="Footer"}
    {block name="Include_Footer_Template"}
        {include file="../global/footer.tpl"}
    {/block}
{/block}
</body>

{block name="Include_Scripts"}
    {foreach $template.resources.js as $js}
        <script src="{$js}"></script>
    {/foreach}
{/block}

</html>