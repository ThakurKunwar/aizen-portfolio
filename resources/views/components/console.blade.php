@props(['routes'])

<div class="console" data-console>
    <div class="console-bar">
        <span class="flags" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></span>
        <span class="console-status" data-status>Connecting...</span>
        <button type="button" class="console-copy" data-copy>Copy curl</button>
    </div>

    <pre class="console-out" data-out tabindex="0" aria-live="polite" aria-label="API response">
<noscript>JavaScript is off, so the console cannot run here. The same data is at {{ url('/api/v1') }}.</noscript></pre>

    <div class="console-chips" role="group" aria-label="Example requests">
        @foreach ($routes as $route)
            <button type="button" class="console-chip" data-path="{{ $route['path'] }}" aria-pressed="false">{{ $route['path'] }}</button>
        @endforeach
    </div>

    <label class="console-input">
        <span aria-hidden="true">GET /api/v1</span>
        <input type="text" data-input placeholder="/projects?language=PHP" autocomplete="off" autocapitalize="off" spellcheck="false" aria-label="Request path, for example /projects. Type help to list routes.">
    </label>
</div>
