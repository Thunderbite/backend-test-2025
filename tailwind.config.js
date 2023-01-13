module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
	theme: {
		extend: {
			backgroundColor: {
                'primary': '#fafafa',
                'form': '#fbfbfd',
                'toggle-success': '#02e284',
            },
            borderColor: {
                'form': '#e7e8f1',
            },
		},
	},
    plugins: [
    ]
}
