$content = Get-Content 'app/Http/Controllers/PageController.php' -Raw
$pattern = "(public function profilKantor\(\))\s*\{(\s*return view\([^)]+\);)\s*\}"
$replacement = "`$1`n    {`n        `$2`n    }`n`n    /**`n     * PPID page.`n     */`n    public function ppid()`n    {`n        return view('ppid');`n    }`n`n"
$content -replace $pattern, $replacement | Set-Content 'app/Http/Controllers/PageController.php' -NoNewline
