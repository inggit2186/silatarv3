{{-- Section: text --}}
{{-- Rich text content using Quill editor --}}
<div class="section-block" data-section-id="{{ $section->id }}">
    <div class="ppid-editor-block-head">
        <span class="ppid-editor-block-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            Konten Teks
        </span>
    </div>

    <x-ui.quill-editor
        :name="'sections[' . $section->id . '][content]'"
        :id="'quill-' . $section->id"
        :label="''"
        :content="old('sections.' . $section->id . '.content', $section->content ?? '')"
    />
</div>
