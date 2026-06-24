<x-admin.layouts.app title="{{ $title }}">
    <div class="min-h-screen">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/20 border border-cyan-500/30">
                    <svg class="h-5 w-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                Tambah Berita Baru
            </h1>
            <p class="mt-2 text-sm text-slate-400">Buat berita atau pengumuman baru untuk portal SILATAR</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title -->
                    <div class="rounded-xl border border-cyan-500/20 bg-slate-900/60 p-6">
                        <label class="mb-3 block text-sm font-mono font-semibold uppercase tracking-wider text-cyan-400">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                Judul Berita
                            </span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-white placeholder-slate-500 focus:border-cyan-400 focus:outline-none focus:shadow-[0_0_15px_rgba(0,212,255,0.3)]" placeholder="Masukkan judul berita...">
                        @error('title')
                        <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="rounded-xl border border-cyan-500/20 bg-slate-900/60 p-6">
                        <label class="mb-3 block text-sm font-mono font-semibold uppercase tracking-wider text-cyan-400">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Ringkasan
                            </span>
                        </label>
                        <textarea name="excerpt" rows="3" required class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-sm text-white placeholder-slate-500 focus:border-cyan-400 focus:outline-none focus:shadow-[0_0_15px_rgba(0,212,255,0.3)]" placeholder="Ringkasan singkat berita (maks500 karakter)...">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                        <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="rounded-xl border border-cyan-500/20 bg-slate-900/60 p-6">
                        <label class="mb-3 block text-sm font-mono font-semibold uppercase tracking-wider text-cyan-400">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                Konten Berita
                            </span>
                        </label>

                        <!-- Toolbar -->
                        <div class="mb-3 flex flex-wrap items-center gap-1 rounded-lg border border-cyan-500/30 bg-slate-900/60 p-2">
                            <!-- Text Format -->
                            <div class="flex items-center gap-1 border-r border-cyan-500/20 pr-2">
                                <select id="fontSelect" onchange="execCmd('fontName', this.value)" class="cyber-editor-select">
                                    <option value="Arial">Arial</option>
                                    <option value="Courier New">Courier New</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Verdana">Verdana</option>
                                </select>
                                <select id="fontSizeSelect" onchange="execCmd('fontSize', this.value)" class="cyber-editor-select w-16">
                                    <option value="1">10px</option>
                                    <option value="2">12px</option>
                                    <option value="3">14px</option>
                                    <option value="4" selected>16px</option>
                                    <option value="5">18px</option>
                                    <option value="6">24px</option>
                                    <option value="7">36px</option>
                                </select>
                            </div>

                            <!-- Bold Italic Underline -->
                            <div class="flex items-center gap-1 border-r border-cyan-500/20 pr-2">
                                <button type="button" onclick="execCmd('bold')" class="cyber-editor-btn" title="Bold (Ctrl+B)">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('italic')" class="cyber-editor-btn" title="Italic (Ctrl+I)">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 4h-9M14 20H5M15 4L9 20"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('underline')" class="cyber-editor-btn" title="Underline (Ctrl+U)">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><path d="M4 21h16"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('strikeThrough')" class="cyber-editor-btn" title="Strikethrough">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 4H8v4h8z"/><path d="M12 8v8"/><path d="M4 12h16"/></svg>
                                </button>
                            </div>

                            <!-- Text Color -->
                            <div class="flex items-center gap-1 border-r border-cyan-500/20 pr-2">
                                <div class="relative">
                                    <button type="button" class="cyber-editor-btn" title="Text Color" onclick="document.getElementById('colorPicker').click()">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                                    </button>
                                    <input type="color" id="colorPicker" onchange="execCmd('foreColor', this.value)" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                                </div>
                                <div class="relative">
                                    <button type="button" class="cyber-editor-btn" title="Highlight" onclick="document.getElementById('highlightPicker').click()">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15.232 5.232l3.536 3.536M9 11l-5 5h5l5-5"/><path d="M3 17V7h6l3-3 7 7-3 3H3z"/></svg>
                                    </button>
                                    <input type="color" id="highlightPicker" onchange="execCmd('hiliteColor', this.value)" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                                </div>
                            </div>

                            <!-- Alignment -->
                            <div class="flex items-center gap-1 border-r border-cyan-500/20 pr-2">
                                <button type="button" onclick="execCmd('justifyLeft')" class="cyber-editor-btn" title="Align Left">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h12M3 18h18"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('justifyCenter')" class="cyber-editor-btn" title="Align Center">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M6 12h12M3 18h18"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('justifyRight')" class="cyber-editor-btn" title="Align Right">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M9 12h12M3 18h18"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('justifyFull')" class="cyber-editor-btn" title="Justify">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                                </button>
                            </div>

                            <!-- Lists -->
                            <div class="flex items-center gap-1 border-r border-cyan-500/20 pr-2">
                                <button type="button" onclick="execCmd('insertUnorderedList')" class="cyber-editor-btn" title="Bullet List">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('insertOrderedList')" class="cyber-editor-btn" title="Numbered List">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 6h11M10 12h11M10 18h11M4 6h1v4M4 10h2M4 14h2l1 2v2h-2M7 18v-4"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('indent')" class="cyber-editor-btn" title="Increase Indent">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><path d="M9 5h6v4H9zM5 12h6l-3 4M5 16h6"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('outdent')" class="cyber-editor-btn" title="Decrease Indent">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><path d="M9 5h6v4H9zM5 12h6l-3 4M5 16h6"/></svg>
                                </button>
                            </div>

                            <!-- Paragraph & Headers -->
                            <div class="flex items-center gap-1 border-r border-cyan-500/20 pr-2">
                                <button type="button" onclick="execCmd('formatBlock', 'h1')" class="cyber-editor-btn" title="Heading 1">H1</button>
                                <button type="button" onclick="execCmd('formatBlock', 'h2')" class="cyber-editor-btn" title="Heading 2">H2</button>
                                <button type="button" onclick="execCmd('formatBlock', 'h3')" class="cyber-editor-btn" title="Heading 3">H3</button>
                                <button type="button" onclick="execCmd('formatBlock', 'p')" class="cyber-editor-btn" title="Paragraph">P</button>
                            </div>

                            <!-- Link & Image -->
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="insertLink()" class="cyber-editor-btn" title="Insert Link">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                                </button>
                                <button type="button" onclick="openImageModal()" class="cyber-editor-btn" title="Insert Image">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('removeFormat')" class="cyber-editor-btn text-rose-400" title="Clear Format">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Editor -->
                        <div id="editor" contenteditable="true" class="min-h-[300px] w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 p-4 text-white focus:border-cyan-400 focus:outline-none focus:shadow-[0_0_15px_rgba(0,212,255,0.3)]" oninput="updateHiddenInput()">{{ old('content') }}</div>
                        <input type="hidden" name="content" id="contentInput" value="{{ old('content') }}">

                        @error('content')
                        <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Separator -->
                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-700/50"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-slate-900/80 px-4 text-xs font-mono uppercase tracking-wider text-slate-500">SEO Settings</span>
                        </div>
                    </div>

                    <!-- SEO Section -->
                    <div class="rounded-xl border border-violet-500/20 bg-slate-900/60 p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="h-5 w-5 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h3 class="text-sm font-mono font-semibold uppercase tracking-wider text-violet-400">SEO Settings</h3>
                            <span class="text-xs text-slate-500 ml-auto">Optimasi untuk Google</span>
                        </div>

                        <!-- Meta Title -->
                        <div class="mb-4">
                            <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">
                                Meta Title
                                <span class="text-emerald-400 ml-1">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" maxlength="70" class="w-full rounded-lg border border-violet-500/30 bg-slate-900/80 px-4 py-2.5 font-mono text-sm text-white placeholder-slate-500 focus:border-violet-400 focus:outline-none focus:shadow-[0_0_15px_rgba(168,85,247,0.2)]" placeholder="Judul untuk SEO (jika kosong, gunakan judul berita)...">
                                <span id="meta_title_count" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">0/70</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Judul yang akan muncul di hasil pencarian Google. Disarankan 50-60 karakter.</p>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-4">
                            <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">
                                Meta Description
                                <span class="text-emerald-400 ml-1">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <textarea name="meta_description" id="meta_description" rows="3" maxlength="160" class="w-full rounded-lg border border-violet-500/30 bg-slate-900/80 px-4 py-2.5 font-mono text-sm text-white placeholder-slate-500 focus:border-violet-400 focus:outline-none focus:shadow-[0_0_15px_rgba(168,85,247,0.2)]" placeholder="Deskripsi untuk SEO (jika kosong, gunakan ringkasan)...">{{ old('meta_description') }}</textarea>
                                <span id="meta_desc_count" class="absolute right-3 bottom-3 text-xs text-slate-500">0/160</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Deskripsi singkat yang akan muncul di hasil pencarian. Disarankan 150-160 karakter.</p>
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">
                                Meta Keywords
                                <span class="text-emerald-400 ml-1">(Opsional)</span>
                            </label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="w-full rounded-lg border border-violet-500/30 bg-slate-900/80 px-4 py-2.5 font-mono text-sm text-white placeholder-slate-500 focus:border-violet-400 focus:outline-none focus:shadow-[0_0_15px_rgba(168,85,247,0.2)]" placeholder="kemenag, agama, tanah datar, layanan (pisahkan dengan koma)...">
                            <p class="mt-1 text-xs text-slate-500">Kata kunci untuk membantu mesin pencari. Pisahkan dengan koma.</p>
                        </div>

                        <!-- SEO Preview -->
                        <div class="mt-4 p-4 rounded-lg border border-slate-700/50 bg-slate-900/40">
                            <p class="text-xs font-mono text-slate-500 mb-2 uppercase tracking-wider">Preview Google:</p>
                            <div id="seo_preview" class="font-sans">
                                <p id="seo_preview_title" class="text-lg text-[#b4d7ff] hover:underline cursor-pointer mb-0.5">Judul Berita - SILATAR</p>
                                <p id="seo_preview_url" class="text-sm text-[#bdc3c7] mb-0.5 truncate">tanahdatar.kemenag.go.id/berita/judul-berita</p>
                                <p id="seo_preview_desc" class="text-sm text-[#9aa5b1]">Deskripsi meta akan muncul di sini...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Publish Settings -->
                    <div class="rounded-xl border border-cyan-500/20 bg-slate-900/60 p-6">
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-mono font-semibold uppercase tracking-wider text-cyan-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Pengaturan
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Kategori</label>
                                <select name="category" required class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 px-3 font-mono text-sm text-white focus:border-cyan-400 focus:outline-none">
                                    @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Status</label>
                                <select name="status" required class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 px-3 font-mono text-sm text-white focus:border-cyan-400 focus:outline-none">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Tanggal Publish</label>
                                <input type="datetime-local" name="publish_date" value="{{ old('publish_date', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 px-3 font-mono text-sm text-white focus:border-cyan-400 focus:outline-none">
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 rounded border-cyan-500/30 bg-slate-900/80 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-900">
                                <label for="is_featured" class="text-sm font-mono text-slate-300">Tampilkan di Featured</label>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_slideshow" id="is_slideshow" value="1" {{ old('is_slideshow') ? 'checked' : '' }} class="h-4 w-4 rounded border-cyan-500/30 bg-slate-900/80 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-900">
                                <label for="is_slideshow" class="text-sm font-mono text-slate-300">Tampilkan di Banner Slideshow</label>
                            </div>
                        </div>
                    </div>

                    <!-- Tim Berita -->
                    <div class="rounded-xl border border-cyan-500/20 bg-slate-900/60 p-6">
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-mono font-semibold uppercase tracking-wider text-cyan-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Tim Berita
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">
                                    Penulis
                                    <span class="text-slate-500 ml-1">(pisahkan dengan koma untuk lebih dari 1 orang)</span>
                                </label>
                                <input type="text" name="writer" value="{{ old('writer', $currentUser ?? '') }}" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 px-3 font-mono text-sm text-white placeholder-slate-500 focus:border-cyan-400 focus:outline-none" placeholder="Nama penulis (contoh: Budi, Siti)...">
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Editor</label>
                                <input type="text" name="editor" value="{{ old('editor') }}" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 px-3 font-mono text-sm text-white placeholder-slate-500 focus:border-cyan-400 focus:outline-none" placeholder="Nama editor (opsional)...">
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Fotografer</label>
                                <input type="text" name="photographer" value="{{ old('photographer') }}" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 px-3 font-mono text-sm text-white placeholder-slate-500 focus:border-cyan-400 focus:outline-none" placeholder="Nama fotografer (opsional)...">
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="rounded-xl border border-cyan-500/20 bg-slate-900/60 p-6">
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-mono font-semibold uppercase tracking-wider text-cyan-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Gambar Berita
                        </h3>

                        <div class="relative">
                            <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <label for="imageInput" class="flex min-h-[160px] cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-cyan-500/30 bg-slate-900/40 transition-all hover:border-cyan-400/50 hover:bg-cyan-500/5 overflow-hidden">
                                <div id="imagePreview" class="hidden w-full h-full absolute inset-0">
                                    <img id="previewImg" class="w-full h-full object-cover">
                                </div>
                                <div id="imagePlaceholder" class="flex flex-col items-center justify-center text-center p-4">
                                    <svg class="h-10 w-10 text-slate-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-slate-400">Klik untuk upload gambar</p>
                                    <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, GIF (Maks 2MB)</p>
                                </div>
                            </label>
                        </div>
                        @error('image')
                        <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-3 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-[0_0_25px_rgba(0,212,255,0.4)] transition-all hover:shadow-[0_0_35px_rgba(0,212,255,0.6)] hover:-translate-y-0.5">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Simpan Berita
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="flex items-center justify-center gap-2 rounded-xl border border-slate-500/30 bg-slate-500/10 px-6 py-3 font-mono text-sm font-semibold uppercase tracking-wider text-slate-400 hover:bg-slate-500/20 transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Image Upload Modal -->
        <div id="imageModal" class="fixed inset-0 z-50 hidden">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeImageModal()"></div>
            <!-- Modal Content -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg z-10">
                <div class="bg-slate-900 border border-cyan-500/30 rounded-2xl shadow-2xl shadow-cyan-500/10 overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-cyan-500/20">
                    <h3 class="text-lg font-mono font-bold text-white">Insert Gambar</h3>
                    <button type="button" onclick="closeImageModal()" class="text-slate-400 hover:text-white transition-colors z-20 relative">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 space-y-5 overflow-y-auto max-h-[80vh]">
                    <!-- Upload Area -->
                    <div class="relative">
                        <input type="file" id="contentImageInput" accept="image/*" class="hidden" onchange="handleContentImageSelect(this)">
                        <label for="contentImageInput" id="contentImageDropzone" class="flex flex-col items-center justify-center min-h-[160px] cursor-pointer rounded-xl border-2 border-dashed border-cyan-500/30 bg-slate-800/50 transition-all hover:border-cyan-400/50 hover:bg-cyan-500/5">
                            <div id="contentImagePreview" class="hidden w-full h-full absolute inset-0 p-2">
                                <img id="contentPreviewImg" class="w-full h-full object-contain rounded-lg">
                            </div>
                            <div id="contentImagePlaceholder">
                                <svg class="w-12 h-12 text-slate-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-slate-400">Klik untuk upload gambar</p>
                                <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, GIF, WebP (Maks 5MB)</p>
                            </div>
                        </label>
                        <p id="uploadError" class="hidden mt-2 text-sm text-rose-400"></p>
                    </div>

                    <!-- URL Input (Alternative) -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-700/50"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-slate-900 px-3 text-xs font-mono text-slate-500">atau gunakan URL</span>
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">URL Gambar</label>
                        <input type="url" id="imageUrlInput" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 px-4 py-2.5 font-mono text-sm text-white placeholder-slate-500 focus:border-cyan-400 focus:outline-none" placeholder="https://example.com/image.jpg" oninput="previewUrlImage()">
                    </div>

                    <!-- Size Controls -->
                    <div>
                        <label class="mb-3 block text-xs font-mono uppercase tracking-wider text-slate-400">Ukuran Gambar</label>
                        <div class="grid grid-cols-4 gap-2" id="sizeButtons">
                            <button type="button" onclick="setImageSize('small')" class="image-size-btn size-btn px-3 py-2 rounded-lg border border-cyan-500/30 bg-slate-800/50 text-sm font-mono text-slate-300 hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer" data-size="small">
                                <span class="block text-xs">Kecil</span>
                                <span class="block text-xs text-slate-500">300px</span>
                            </button>
                            <button type="button" onclick="setImageSize('medium')" class="image-size-btn size-btn px-3 py-2 rounded-lg border border-cyan-500/30 bg-slate-800/50 text-sm font-mono text-slate-300 hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer active" data-size="medium">
                                <span class="block text-xs">Sedang</span>
                                <span class="block text-xs text-slate-500">500px</span>
                            </button>
                            <button type="button" onclick="setImageSize('large')" class="image-size-btn size-btn px-3 py-2 rounded-lg border border-cyan-500/30 bg-slate-800/50 text-sm font-mono text-slate-300 hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer" data-size="large">
                                <span class="block text-xs">Besar</span>
                                <span class="block text-xs text-slate-500">800px</span>
                            </button>
                            <button type="button" onclick="setImageSize('full')" class="image-size-btn size-btn px-3 py-2 rounded-lg border border-cyan-500/30 bg-slate-800/50 text-sm font-mono text-slate-300 hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer" data-size="full">
                                <span class="block text-xs">Penuh</span>
                                <span class="block text-xs text-slate-500">100%</span>
                            </button>
                        </div>
                    </div>

                    <!-- Alignment -->
                    <div>
                        <label class="mb-3 block text-xs font-mono uppercase tracking-wider text-slate-400">Perataan</label>
                        <div class="grid grid-cols-4 gap-2" id="alignButtons">
                            <button type="button" onclick="setImageAlign('left')" class="image-align-btn align-btn px-3 py-2 rounded-lg border border-cyan-500/30 bg-slate-800/50 text-sm font-mono text-slate-300 hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer" data-align="left">
                                <svg class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h12M3 18h18"/></svg>
                            </button>
                            <button type="button" onclick="setImageAlign('center')" class="image-align-btn align-btn px-3 py-2 rounded-lg border border-cyan-500/30 bg-cyan-500/10 text-sm font-mono text-cyan-400 hover:border-cyan-400 hover:bg-cyan-500/20 transition-all cursor-pointer active" data-align="center">
                                <svg class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M6 12h12M3 18h18"/></svg>
                            </button>
                            <button type="button" onclick="setImageAlign('right')" class="image-align-btn align-btn px-3 py-2 rounded-lg border border-cyan-500/30 bg-slate-800/50 text-sm font-mono text-slate-300 hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer" data-align="right">
                                <svg class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 6h18M9 12h12M3 18h18"/></svg>
                            </button>
                            <button type="button" onclick="setImageAlign('none')" class="image-align-btn align-btn px-3 py-2 rounded-lg border border-cyan-500/30 bg-slate-800/50 text-sm font-mono text-slate-300 hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer" data-align="none">
                                <svg class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16" stroke-dasharray="3 3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Caption -->
                    <div>
                        <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Caption</label>
                        <input type="text" id="imageCaption" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 px-4 py-2.5 font-mono text-sm text-white placeholder-slate-500 focus:border-cyan-400 focus:outline-none" placeholder="Caption gambar (opsional)">
                    </div>
                </div>
                <div class="flex gap-3 p-4 border-t border-cyan-500/20 bg-slate-900/50">
                    <button type="button" onclick="closeImageModal()" class="flex-1 rounded-xl border border-slate-500/30 bg-slate-800/50 px-4 py-2.5 font-mono text-sm font-semibold text-slate-400 hover:bg-slate-700/50 transition-all">
                        Batal
                    </button>
                    <button type="button" onclick="insertContentImage()" class="flex-1 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-4 py-2.5 font-mono text-sm font-bold text-white shadow-[0_0_20px_rgba(0,212,255,0.3)] hover:shadow-[0_0_30px_rgba(0,212,255,0.5)] transition-all">
                        Insert Gambar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle Alpine.js $persist error gracefully
        window.addEventListener('error', function(e) {
            if (e.message && e.message.includes('$persist')) {
                console.warn('Alpine $persist warning suppressed');
                e.preventDefault();
                return false;
            }
        });

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('imagePlaceholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Rich Text Editor Functions
        function execCmd(command, value = null) {
            document.execCommand(command, false, value);
            document.getElementById('editor').focus();
            updateHiddenInput();
        }

        function updateHiddenInput() {
            document.getElementById('contentInput').value = document.getElementById('editor').innerHTML;
        }

        function insertLink() {
            var url = prompt('Masukkan URL:', 'https://');
            if (url) {
                execCmd('createLink', url);
            }
        }

        // Image Modal Functions - using simple variables
        let pendingImageUrl = null;
        let pendingImageFile = null;
        let currentImageSize = 'medium';
        let currentImageAlign = 'center';
        let savedSelection = null;

        function openImageModal() {
            // Save current cursor position before opening modal
            const editor = document.getElementById('editor');
            const selection = window.getSelection();
            if (selection.rangeCount > 0 && editor.contains(selection.anchorNode)) {
                savedSelection = selection.getRangeAt(0).cloneRange();
            } else {
                savedSelection = null;
            }
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function resetImageModal() {
            document.getElementById('contentImageInput').value = '';
            document.getElementById('imageUrlInput').value = '';
            document.getElementById('imageCaption').value = '';
            document.getElementById('contentImagePreview').classList.add('hidden');
            document.getElementById('contentImagePlaceholder').classList.remove('hidden');
            document.getElementById('uploadError').classList.add('hidden');
            pendingImageUrl = null;
            pendingImageFile = null;
            currentImageSize = 'medium';
            currentImageAlign = 'center';
        }

        function handleContentImageSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxSize = 5 * 1024 * 1024;

                if (file.size > maxSize) {
                    document.getElementById('uploadError').textContent = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    document.getElementById('uploadError').classList.remove('hidden');
                    return;
                }

                document.getElementById('uploadError').classList.add('hidden');
                pendingImageFile = file;

                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('contentPreviewImg').src = e.target.result;
                    document.getElementById('contentImagePreview').classList.remove('hidden');
                    document.getElementById('contentImagePlaceholder').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function previewUrlImage() {
            const url = document.getElementById('imageUrlInput').value;
            if (url) {
                document.getElementById('contentPreviewImg').src = url;
                document.getElementById('contentImagePreview').classList.remove('hidden');
                document.getElementById('contentImagePlaceholder').classList.add('hidden');
                pendingImageUrl = url;
                pendingImageFile = null;
            } else {
                document.getElementById('contentImagePreview').classList.add('hidden');
                document.getElementById('contentImagePlaceholder').classList.remove('hidden');
                pendingImageUrl = null;
            }
        }

        function setImageSize(size) {
            currentImageSize = size;
            document.querySelectorAll('.size-btn').forEach(btn => {
                if (btn.dataset.size === size) {
                    btn.classList.add('border-cyan-400', 'bg-cyan-500/20', 'text-cyan-400');
                    btn.classList.remove('text-slate-300');
                    btn.style.borderColor = '#22d3ee';
                    btn.style.backgroundColor = 'rgba(6, 182, 212, 0.2)';
                    btn.style.color = '#22d3ee';
                } else {
                    btn.classList.remove('border-cyan-400', 'bg-cyan-500/20', 'text-cyan-400');
                    btn.classList.add('text-slate-300');
                    btn.style.borderColor = '';
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                }
            });
        }

        function setImageAlign(align) {
            currentImageAlign = align;
            document.querySelectorAll('.align-btn').forEach(btn => {
                if (btn.dataset.align === align) {
                    btn.classList.add('border-cyan-400', 'bg-cyan-500/10', 'text-cyan-400');
                    btn.classList.remove('text-slate-300');
                    btn.style.borderColor = '#22d3ee';
                    btn.style.backgroundColor = 'rgba(6, 182, 212, 0.1)';
                    btn.style.color = '#22d3ee';
                } else {
                    btn.classList.remove('border-cyan-400', 'bg-cyan-500/10', 'text-cyan-400');
                    btn.classList.add('text-slate-300');
                    btn.style.borderColor = '';
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                }
            });
        }

        function insertContentImage() {
            const urlInput = document.getElementById('imageUrlInput').value;
            const caption = document.getElementById('imageCaption').value;

            if (pendingImageFile) {
                const formData = new FormData();
                formData.append('image', pendingImageFile);

                fetch('{{ route("admin.news.upload-image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        insertImageElement(data.url, caption);
                        if (data.stats) showImageStats(data.stats);
                    } else {
                        alert('Upload gagal: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Error: ' + error.message);
                });
            } else if (urlInput) {
                insertImageElement(urlInput, caption);
            } else {
                alert('Silakan upload gambar atau masukkan URL.');
                return;
            }

            closeImageModal();
        }

        function insertImageElement(src, caption) {
            const sizes = {
                'small': '300px',
                'medium': '500px',
                'large': '800px',
                'full': '100%'
            };

            const maxWidth = sizes[currentImageSize] || '500px';
            const align = currentImageAlign === 'none' ? 'justify' : currentImageAlign;

            const figure = document.createElement('figure');
            figure.style.cssText = `margin: 1rem 0; text-align: ${align};`;
            figure.innerHTML = `
                <img src="${src}" alt="${caption || 'Image'}" style="max-width: ${maxWidth}; width: 100%; height: auto; border-radius: 8px; display: block; margin: 0 auto;">
                ${caption ? `<figcaption style="font-size: 0.875rem; color: #94a3b8; margin-top: 0.5rem; font-style: italic;">${caption}</figcaption>` : ''}
            `;

            const editor = document.getElementById('editor');
            if (editor) {
                // Parse HTML to get the figure element
                const temp = document.createElement('div');
                temp.innerHTML = `<figure style="margin: 1rem 0; text-align: ${align};">
                    <img src="${src}" alt="${caption || 'Image'}" style="max-width: ${maxWidth}; width: 100%; height: auto; border-radius: 8px; display: block; margin: 0 auto;">
                    ${caption ? `<figcaption style="font-size: 0.875rem; color: #94a3b8; margin-top: 0.5rem; font-style: italic;">${caption}</figcaption>` : ''}
                </figure>`;
                const imgElement = temp.firstChild;

                // Try saved selection first (from before modal opened)
                if (savedSelection) {
                    try {
                        const range = savedSelection.cloneRange();
                        range.deleteContents();

                        // Insert a paragraph break first if we're in the middle of text
                        const parent = range.startContainer;
                        if (parent.nodeType === Node.TEXT_NODE && range.startOffset > 0) {
                            const textBefore = parent.textContent.substring(0, range.startOffset);
                            const textAfter = parent.textContent.substring(range.startOffset);
                            const parentP = parent.parentElement;

                            if (textBefore.trim()) {
                                parent.textContent = textBefore;
                                const afterSpan = document.createElement('span');
                                afterSpan.textContent = textAfter;
                                parentP.appendChild(afterSpan);
                                range.setStart(afterSpan, 0);
                                range.collapse(true);
                            }
                        }

                        // Insert paragraph breaks and the image
                        const pBefore = document.createElement('p');
                        pBefore.innerHTML = '<br>';
                        const pAfter = document.createElement('p');
                        pAfter.innerHTML = '<br>';

                        range.insertNode(pBefore);
                        range.insertNode(imgElement);
                        range.insertNode(pAfter);

                        // Move cursor after the image
                        const newRange = document.createRange();
                        newRange.setStartAfter(pAfter);
                        newRange.collapse(true);
                        const sel = window.getSelection();
                        sel.removeAllRanges();
                        sel.addRange(newRange);

                        editor.focus();
                        updateHiddenInput();
                        savedSelection = null;
                        return;
                    } catch (e) {
                        console.error('Insert error:', e);
                        savedSelection = null;
                    }
                }

                // Try current cursor position
                const selection = window.getSelection();
                if (selection.rangeCount > 0 && editor.contains(selection.anchorNode)) {
                    const range = selection.getRangeAt(0);
                    range.deleteContents();

                    const pBefore = document.createElement('p');
                    pBefore.innerHTML = '<br>';
                    const pAfter = document.createElement('p');
                    pAfter.innerHTML = '<br>';

                    range.insertNode(pBefore);
                    range.insertNode(imgElement);
                    range.insertNode(pAfter);

                    const newRange = document.createRange();
                    newRange.setStartAfter(pAfter);
                    newRange.collapse(true);
                    selection.removeAllRanges();
                    selection.addRange(newRange);

                    editor.focus();
                } else {
                    // Fallback: insert at end
                    const pBefore = document.createElement('p');
                    pBefore.innerHTML = '<br>';
                    const pAfter = document.createElement('p');
                    pAfter.innerHTML = '<br>';
                    editor.appendChild(pBefore);
                    editor.appendChild(imgElement);
                    editor.appendChild(pAfter);

                    const newRange = document.createRange();
                    newRange.setStartAfter(pAfter);
                    newRange.collapse(true);
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(newRange);

                    editor.focus();
                }
            }
            updateHiddenInput();
        }
        console.log('Script loaded, editor ID:', document.getElementById('editor')?.id);

        // Show compression stats toast
        function showImageStats(stats) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 z-50 bg-emerald-500/90 backdrop-blur-sm text-white px-4 py-3 rounded-xl shadow-lg border border-emerald-400/30 flex items-center gap-3';
            toast.innerHTML = `
                <svg class="w-5 h-5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <div class="text-sm">
                    <p class="font-semibold">Gambar dikompres!</p>
                    <p class="text-xs text-emerald-200">Ukuran: ${stats.original_size} → ${stats.compressed_size} (hemat ${stats.saved_percent})</p>
                    <p class="text-xs text-emerald-200">Dimensi: ${stats.dimensions}</p>
                </div>
            `;
            document.body.appendChild(toast);

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            updateHiddenInput();
            initSEOPreview();
        });

        // SEO Preview Functions
        function initSEOPreview() {
            const titleInput = document.querySelector('input[name="title"]');
            const metaTitleInput = document.getElementById('meta_title');
            const metaDescInput = document.getElementById('meta_description');
            const excerptInput = document.querySelector('textarea[name="excerpt"]');

            // Character counters
            if (metaTitleInput) {
                updateCounter(metaTitleInput, 'meta_title_count', 70);
                metaTitleInput.addEventListener('input', updateSEOPreview);
            }
            if (metaDescInput) {
                updateCounter(metaDescInput, 'meta_desc_count', 160);
                metaDescInput.addEventListener('input', updateSEOPreview);
            }

            // Title input listener
            if (titleInput) {
                titleInput.addEventListener('input', updateSEOPreview);
            }
            if (excerptInput) {
                excerptInput.addEventListener('input', updateSEOPreview);
            }

            // Initial update
            updateSEOPreview();
        }

        function updateCounter(input, counterId, max) {
            const counter = document.getElementById(counterId);
            input.addEventListener('input', function() {
                const count = this.value.length;
                counter.textContent = count + '/' + max;
                counter.className = count > max ? 'absolute right-3 top-1/2 -translate-y-1/2 text-xs text-rose-400' : 'absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500';
            });
        }

        function updateSEOPreview() {
            const title = document.querySelector('input[name="title"]')?.value || 'Judul Berita';
            const metaTitle = document.getElementById('meta_title')?.value || title;
            const metaDesc = document.getElementById('meta_description')?.value || document.querySelector('textarea[name="excerpt"]')?.value || '';
            const slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

            const previewTitle = document.getElementById('seo_preview_title');
            const previewUrl = document.getElementById('seo_preview_url');
            const previewDesc = document.getElementById('seo_preview_desc');

            if (previewTitle) previewTitle.textContent = metaTitle.length > 60 ? metaTitle.substring(0, 57) + '...' : metaTitle;
            if (previewUrl) previewUrl.textContent = 'tanahdatar.kemenag.go.id/berita/' + (slug || 'judul-berita');
            if (previewDesc) previewDesc.textContent = metaDesc.length > 160 ? metaDesc.substring(0, 157) + '...' : metaDesc;
        }
    </script>
</x-admin.layouts.app>

<style>
    .cyber-editor-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        color: #94a3b8;
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 12px;
        font-weight: 700;
    }

    .cyber-editor-btn:hover {
        background: rgba(0, 212, 255, 0.15);
        color: #00f0ff;
        border-color: rgba(0, 212, 255, 0.3);
    }

    .cyber-editor-btn:active {
        background: rgba(0, 212, 255, 0.25);
        transform: scale(0.95);
    }

    .cyber-editor-select {
        width: 90px;
        padding: 4px 8px;
        border-radius: 6px;
        background: rgba(15, 23, 42, 0.9);
        border: 1px solid rgba(0, 212, 255, 0.2);
        color: #e2e8f0;
        font-size: 11px;
        cursor: pointer;
    }

    .cyber-editor-select:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
    }

    #editor {
        min-height: 300px;
        line-height: 1.8;
        overflow: visible;
    }

    #editor h1 { font-size: 2em; font-weight: bold; color: #fff; margin: 0.5em 0; }
    #editor h2 { font-size: 1.5em; font-weight: bold; color: #fff; margin: 0.5em 0; }
    #editor h3 { font-size: 1.25em; font-weight: bold; color: #fff; margin: 0.5em 0; }
    #editor p { margin: 0.5em 0; }
    #editor ul, #editor ol { margin: 0.5em 0; padding-left: 1.5em; }
    #editor a { color: #00d4ff; text-decoration: underline; }
</style>