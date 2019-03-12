{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_homeblock" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-md-8">
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="content[{$id}][name_homeblock]">{#title#|ucfirst} :</label>
                                        <input type="text" class="form-control" id="content[{$id}][name_homeblock]" name="content[{$id}][name_homeblock]" value="{$pages.content[{$id}].name_homeblock}" size="50" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="content[{$id}][published_homeblock]">Statut</label>
                                        <input id="content[{$id}][published_homeblock]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_homeblock]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($pages) && $iso@first) || $pages.content[{$id}].published_homeblock} checked{/if}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="content[{$id}][content_homeblock]">{#content#|ucfirst} :</label>
                                        <textarea name="content[{$id}][content_homeblock]" id="content[{$id}][content_homeblock]" class="form-control mceEditor">{call name=cleantextarea field=$pages.content[{$id}].content_homeblock}</textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    {/foreach}
                </div>
            </div>
        </div>
        <input type="hidden" id="id_homeblock" name="id" value="{$pages.id_homeblock}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>