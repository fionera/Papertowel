<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                {block name="Body_Navigation_Logo_Img"}
                    <img src="{$template.logoURL}" alt="{$template.site_name}">
                {/block}
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                {block name="Body_Navigation_Items"}
                    {include file="string:`$template['menuBuilder']`"}
                {/block}
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="100"
                       data-close-others="true" role="button" aria-haspopup="true"
                       aria-expanded="false">
                        {$language.languages[$language.selected]}
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        {foreach $language.languages as $key => $value}
                            <li {if $language.selected == $key}class="active"{/if}>
                                <a onclick="setParam('language', '{$key}');">
                                    {$value}
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

