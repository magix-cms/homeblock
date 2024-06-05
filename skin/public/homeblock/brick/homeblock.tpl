{widget_homeblock_data}
{if isset($homeblock) && $homeblock != null}
    <section id="homeblock" class="clearfix">
    <h3>{$homeblock.name_homeblock}</h3>
    {$homeblock.content_homeblock}
    </section>
{/if}