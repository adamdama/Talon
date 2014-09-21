{# app/views/macros.volt #}

{%- macro svg_icon(href) %}
    <svg>
        <use xlink:href="{{ href }}"></use>
    </svg>
{%- endmacro %}