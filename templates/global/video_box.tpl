<div>
    <video>
        {foreach $videoSource as $video}
            <source src="{$video.src}">
        {/foreach}
    </video>
</div>