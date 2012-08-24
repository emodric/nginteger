Hello from legacy eZ Publish!

<div class="block">
    <div class="element">
        <label>First number:</label>
        <p>
            {if $content.data_map.nginteger.has_content}{$content.data_map.nginteger.content.first_number}{else}No data{/if}
        </p>
    </div>

    <div class="element">
        <label>Second number:</label>
        <p>
            {if $content.data_map.nginteger.has_content}{$content.data_map.nginteger.content.second_number}{else}No data{/if}
        </p>
    </div>

    <div class="break"></div>
</div>
