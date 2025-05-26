<script setup>
defineProps({
    articles: Array,
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow">
            <div
                class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center"
            >
                <h1 class="text-2xl font-bold text-indigo-600">
                    SIAKAD Article Hub
                </h1>
                <a href="/login" class="text-sm text-indigo-600 hover:underline"
                    >Login</a
                >
            </div>
        </header>

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-6">
                    Artikel Terbaru
                </h2>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="(article, index) in articles"
                        :key="index"
                        class="bg-white rounded-xl shadow p-4 border border-gray-200 hover:shadow-md transition"
                    >
                        <h3 class="text-lg font-bold text-gray-800 mb-1">
                            {{ article.judul }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-1">
                            <span class="font-semibold">Mahasiswa:</span>
                            {{ article.mahasiswa || "-" }}
                        </p>
                        <p class="text-sm text-gray-600 mb-1">
                            <span class="font-semibold">Dosen:</span>
                            {{ article.dosen || "-" }}
                        </p>
                        <p class="text-sm text-gray-500">
                            <span class="font-semibold">Tipe:</span>
                            {{ article.tipe }} |
                            <span class="ml-2">{{ article.created_at }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <footer
            class="bg-white mt-12 py-4 border-t text-center text-sm text-gray-500"
        >
            © {{ new Date().getFullYear() }} SIAKAD App. All rights reserved.
        </footer>
    </div>
</template>
