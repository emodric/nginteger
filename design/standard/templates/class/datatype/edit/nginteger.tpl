<div class="block"></div>

<div class="block">
    <label>Minimum for first number:</label> <input type="text" maxlength="11" size="11" name="ContentClass_nginteger_first_number_min_{$class_attribute.id}" value="{if $class_attribute.data_int1|ge(0)}{$class_attribute.data_int1}{/if}" />
</div>

<div class="block">
    <label>Maximum for first number:</label> <input type="text" maxlength="11" size="11" name="ContentClass_nginteger_first_number_max_{$class_attribute.id}" value="{if $class_attribute.data_int2|ge(0)}{$class_attribute.data_int2}{/if}" />
</div>

<div class="block">
    <label>Minimum for second number:</label> <input type="text" maxlength="11" size="11" name="ContentClass_nginteger_second_number_min_{$class_attribute.id}" value="{if $class_attribute.data_int3|ge(0)}{$class_attribute.data_int3}{/if}" />
</div>

<div class="block">
    <label>Maximum for second number:</label> <input type="text" maxlength="11" size="11" name="ContentClass_nginteger_second_number_max_{$class_attribute.id}" value="{if $class_attribute.data_int4|ge(0)}{$class_attribute.data_int4}{/if}" />
</div>
