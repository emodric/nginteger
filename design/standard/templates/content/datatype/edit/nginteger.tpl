{default attribute_base=ContentObjectAttribute}

<div class="block">
    <label>First number:</label> <input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" type="text" maxlength="11" size="11" name="{$attribute_base}_nginteger_first_number_{$attribute.id}" value="{$attribute.content.first_number|wash}"  />
</div>

<div class="block">
    <label>Second number:</label> <input id="ezcoa2-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" type="text" maxlength="11" size="11" name="{$attribute_base}_nginteger_second_number_{$attribute.id}" value="{$attribute.content.second_number|wash}"  />
</div>

{/default}
