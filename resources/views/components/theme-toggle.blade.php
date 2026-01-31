<div x-data="{
    theme: localStorage.getItem('theme') || 'system',
    init() {
        this.applyTheme(this.theme);
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (this.theme === 'system') {
                this.applyTheme('system');
            }
        });
    },
    applyTheme(val) {
        this.theme = val;
        localStorage.setItem('theme', val);
        
        if (val === 'dark' || (val === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" class="relative flex items-center p-1 rounded-full bg-slate-200 dark:bg-slate-700">
    
    <button @click="applyTheme('light')" 
            :class="{'bg-white text-slate-900 shadow-sm': theme === 'light', 'text-slate-500 hover:text-slate-900 dark:text-slate-400': theme !== 'light'}"
            class="p-1.5 rounded-full transition-all duration-200 focus:outline-none">
        <span class="sr-only">Light</span>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </button>
    
    <button @click="applyTheme('system')" 
            :class="{'bg-white text-slate-900 shadow-sm': theme === 'system', 'text-slate-500 hover:text-slate-900 dark:text-slate-400': theme !== 'system'}"
            class="p-1.5 rounded-full transition-all duration-200 focus:outline-none">
        <span class="sr-only">System</span>
         <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
    </button>
    
    <button @click="applyTheme('dark')" 
            :class="{'bg-slate-600 text-white shadow-sm': theme === 'dark', 'text-slate-500 hover:text-slate-900 dark:text-slate-400': theme !== 'dark'}"
            class="p-1.5 rounded-full transition-all duration-200 focus:outline-none">
        <span class="sr-only">Dark</span>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>
