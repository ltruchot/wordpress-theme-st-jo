import globals from 'globals';
import js from '@eslint/js';
import prettier from 'eslint-config-prettier';

export default [
	{
		// .claude/skills holds a verbatim copy of the official WordPress Agent
		// Skills. Linting them enforces this project's house style on code that is
		// not ours and that we deliberately do not modify -- see LEADS.md. Their
		// helper scripts are Node modules, which this config does not describe, so
		// every `process` reads as undefined.
		ignores: ['**/*.min.js', '**/vendor/', '.claude/**'],
	},
	{
		files: ['**/*.{js,mjs}'],
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'script',
		},
		rules: {
			...js.configs.recommended.rules,
			...prettier.rules,
		},
	},
	{
		files: ['javascript/**/*.js', '**/*.mjs'],
		languageOptions: {
			sourceType: 'module',
		},
	},
	{
		files: ['javascript/**/*.js'],
		languageOptions: {
			globals: {
				...globals.browser,
				wp: 'readonly',
			},
		},
	},
	{
		files: ['node_scripts/*.js', 'tailwind/*.js'],
		languageOptions: {
			globals: {
				...globals.node,
			},
		},
	},
];
