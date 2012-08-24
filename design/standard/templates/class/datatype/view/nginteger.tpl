<div class="block">
    <div class="element">
        <label>Minimum for first number:</label>
        <p>
            {if $class_attribute.data_int1|ge(0)}{$class_attribute.data_int1}{else}No minimum{/if}
        </p>
    </div>

    <div class="element">
        <label>Maximum for first number:</label>
        <p>
            {if $class_attribute.data_int2|ge(0)}{$class_attribute.data_int2}{else}No maximum{/if}
        </p>
    </div>

    <div class="element">
        <label>Minimum for second number:</label>
        <p>
            {if $class_attribute.data_int3|ge(0)}{$class_attribute.data_int3}{else}No minimum{/if}
        </p>
    </div>

    <div class="element">
        <label>Maximum for second number:</label>
        <p>
            {if $class_attribute.data_int4|ge(0)}{$class_attribute.data_int4}{else}No maximum{/if}
        </p>
    </div>

    <div class="break"></div>
</div>
