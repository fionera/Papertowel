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