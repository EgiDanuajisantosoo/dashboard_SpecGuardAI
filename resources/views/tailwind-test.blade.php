<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind CSS Test Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
                Tailwind CSS Test Page
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Testing berbagai komponen dan styling Tailwind CSS
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Colors Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Colors & Text
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 bg-red-500 text-white rounded-lg">Red - bg-red-500</div>
                <div class="p-4 bg-blue-500 text-white rounded-lg">Blue - bg-blue-500</div>
                <div class="p-4 bg-green-500 text-white rounded-lg">Green - bg-green-500</div>
                <div class="p-4 bg-purple-500 text-white rounded-lg">Purple - bg-purple-500</div>
                <div class="p-4 bg-yellow-500 text-gray-900 rounded-lg">Yellow - bg-yellow-500</div>
                <div class="p-4 bg-pink-500 text-white rounded-lg">Pink - bg-pink-500</div>
                <div class="p-4 bg-indigo-500 text-white rounded-lg">Indigo - bg-indigo-500</div>
                <div class="p-4 bg-teal-500 text-white rounded-lg">Teal - bg-teal-500</div>
            </div>
        </section>

        <!-- Typography Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Typography
            </h2>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                <div class="text-xs text-gray-600 dark:text-gray-400">Extra Small (text-xs)</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Small (text-sm)</div>
                <div class="text-base text-gray-600 dark:text-gray-400">Base (text-base)</div>
                <div class="text-lg text-gray-600 dark:text-gray-400">Large (text-lg)</div>
                <div class="text-xl text-gray-600 dark:text-gray-400">Extra Large (text-xl)</div>
                <div class="text-2xl text-gray-600 dark:text-gray-400">2xl (text-2xl)</div>
                <div class="text-3xl text-gray-600 dark:text-gray-400">3xl (text-3xl)</div>
                <div class="text-4xl text-gray-600 dark:text-gray-400">4xl (text-4xl)</div>
            </div>
        </section>

        <!-- Font Weight Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Font Weights
            </h2>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                <div class="font-thin text-lg text-gray-700 dark:text-gray-300">Thin (font-thin)</div>
                <div class="font-light text-lg text-gray-700 dark:text-gray-300">Light (font-light)</div>
                <div class="font-normal text-lg text-gray-700 dark:text-gray-300">Normal (font-normal)</div>
                <div class="font-medium text-lg text-gray-700 dark:text-gray-300">Medium (font-medium)</div>
                <div class="font-semibold text-lg text-gray-700 dark:text-gray-300">Semibold (font-semibold)</div>
                <div class="font-bold text-lg text-gray-700 dark:text-gray-300">Bold (font-bold)</div>
                <div class="font-extrabold text-lg text-gray-700 dark:text-gray-300">Extrabold (font-extrabold)</div>
                <div class="font-black text-lg text-gray-700 dark:text-gray-300">Black (font-black)</div>
            </div>
        </section>

        <!-- Buttons Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Buttons & Interactive Elements
            </h2>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                        Primary Button
                    </button>
                    <button class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                        Secondary Button
                    </button>
                    <button class="px-4 py-2 border-2 border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition duration-200">
                        Outline Button
                    </button>
                    <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200">
                        Danger Button
                    </button>
                    <button class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200">
                        Success Button
                    </button>
                    <button class="px-4 py-2 bg-yellow-500 text-gray-900 rounded-lg hover:bg-yellow-600 transition duration-200">
                        Warning Button
                    </button>
                </div>
            </div>
        </section>

        <!-- Cards Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Cards
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="h-40 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            Card Title 1
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            This is a beautiful card component with a gradient header and shadow effect.
                        </p>
                        <button class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                            Learn More
                        </button>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="h-40 bg-gradient-to-r from-green-500 to-teal-600"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            Card Title 2
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Tailwind CSS makes it easy to build responsive and beautiful card components.
                        </p>
                        <button class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200">
                            Learn More
                        </button>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="h-40 bg-gradient-to-r from-pink-500 to-red-600"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            Card Title 3
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            All cards have hover effects and are fully responsive on mobile devices.
                        </p>
                        <button class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200">
                            Learn More
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Spacing Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Spacing & Layout
            </h2>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="space-y-4">
                    <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded text-blue-900 dark:text-blue-100">p-4 (padding: 1rem)</div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded text-blue-900 dark:text-blue-100">p-6 (padding: 1.5rem)</div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-8 rounded text-blue-900 dark:text-blue-100">p-8 (padding: 2rem)</div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-12 rounded text-blue-900 dark:text-blue-100">p-12 (padding: 3rem)</div>
                </div>
            </div>
        </section>

        <!-- Responsive Grid Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Responsive Grid
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="bg-purple-500 text-white p-6 rounded-lg text-center font-semibold">
                    1 col (xs)
                </div>
                <div class="bg-purple-500 text-white p-6 rounded-lg text-center font-semibold">
                    2 col (sm)
                </div>
                <div class="bg-purple-500 text-white p-6 rounded-lg text-center font-semibold">
                    3 col (md)
                </div>
                <div class="bg-purple-500 text-white p-6 rounded-lg text-center font-semibold">
                    4 col (lg)
                </div>
                <div class="bg-purple-400 text-white p-6 rounded-lg text-center font-semibold">
                    Responsive 1
                </div>
                <div class="bg-purple-400 text-white p-6 rounded-lg text-center font-semibold">
                    Responsive 2
                </div>
                <div class="bg-purple-400 text-white p-6 rounded-lg text-center font-semibold">
                    Responsive 3
                </div>
                <div class="bg-purple-400 text-white p-6 rounded-lg text-center font-semibold">
                    Responsive 4
                </div>
            </div>
        </section>

        <!-- Forms Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Form Elements
            </h2>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow max-w-md">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Full Name
                    </label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your name">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Email
                    </label>
                    <input type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="your@email.com">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Message
                    </label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Your message..."></textarea>
                </div>
                <button class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 font-semibold">
                    Submit
                </button>
            </div>
        </section>

        <!-- Alerts Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Alerts
            </h2>
            <div class="space-y-4">
                <div class="p-4 bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-900 dark:text-blue-100">
                    <strong>Info:</strong> This is an informational alert message.
                </div>
                <div class="p-4 bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-900 dark:text-green-100">
                    <strong>Success:</strong> Your changes have been saved successfully.
                </div>
                <div class="p-4 bg-yellow-100 dark:bg-yellow-900 border-l-4 border-yellow-500 text-yellow-900 dark:text-yellow-100">
                    <strong>Warning:</strong> Please review the following items before proceeding.
                </div>
                <div class="p-4 bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-900 dark:text-red-100">
                    <strong>Error:</strong> An error occurred while processing your request.
                </div>
            </div>
        </section>

        <!-- Flexbox Examples -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Flexbox Examples
            </h2>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-6">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 mb-3 font-semibold">Flex Row (justify-center):</p>
                    <div class="flex justify-center gap-4">
                        <div class="bg-pink-500 text-white px-4 py-2 rounded">Item 1</div>
                        <div class="bg-pink-500 text-white px-4 py-2 rounded">Item 2</div>
                        <div class="bg-pink-500 text-white px-4 py-2 rounded">Item 3</div>
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 mb-3 font-semibold">Flex Row (space-between):</p>
                    <div class="flex justify-between">
                        <div class="bg-cyan-500 text-white px-4 py-2 rounded">Left</div>
                        <div class="bg-cyan-500 text-white px-4 py-2 rounded">Center</div>
                        <div class="bg-cyan-500 text-white px-4 py-2 rounded">Right</div>
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 mb-3 font-semibold">Flex Column (items-center):</p>
                    <div class="flex flex-col items-center gap-3">
                        <div class="bg-lime-500 text-white px-4 py-2 rounded w-full text-center">Item 1</div>
                        <div class="bg-lime-500 text-white px-4 py-2 rounded w-full text-center">Item 2</div>
                        <div class="bg-lime-500 text-white px-4 py-2 rounded w-full text-center">Item 3</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <p class="text-center text-gray-400">
                Tailwind CSS v4.0.0 - Test Page | Built with Laravel & Tailwind CSS
            </p>
        </div>
    </footer>
</body>
</html>
