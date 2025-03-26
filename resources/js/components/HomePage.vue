<template>
	<div class="mx-0 sm:mx-6">

		<div class="bg-gray-200 w-full text-xl md:text-2xl text-gray-800 leading-normal rounded-t">

			<!-- Lead Card -->
			<div v-for="area1 in area1PostsData" :key="area1.id"
				class="flex h-full bg-white rounded overflow-hidden shadow-lg">
				<a :href="'/blog/' + area1.slug" class="flex flex-wrap no-underline hover:no-underline">
					<div class="w-full md:w-2/3 rounded-t">
						<img :src="area1.image_url || '/front/22.jpg'" class="h-full w-full shadow" />
					</div>
					<div class="w-full md:w-1/3 flex flex-col flex-grow flex-shrink">
						<div class="flex-1 bg-white rounded-t rounded-b-none overflow-hidden shadow-lg">
							<p class="w-full text-gray-600 text-xs md:text-sm pt-6 px-6">{{ area1.category }}</p>
							<div class="w-full font-bold text-xl text-gray-900 px-6">{{ area1.title }}</div>
							<p class="text-gray-800 font-serif text-base px-6 mb-5">
								{{ showFullContent ? area1.content : truncatedContent(area1.content) }}
							</p>
							<div class="flex justify-center w-full">
								<a :href="'/blog/' + area1.slug" v-if="!showFullContent" @click="showFullContent = true"
									class="devam-butonu text-center font-normal hover:font-bold">
									Devamını Oku
								</a>
							</div>
						</div>
						<div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-6">
							<div class="flex items-center justify-between">
								<div class="flex items-center space-x-2">
									<img class="w-8 h-8 rounded-full mr-4 avatar"
										:src="area1.user && area1.user.avatar ? area1.user.avatar : 'http://i.pravatar.cc/300'"
										alt="Avatar" />
									<p class="text-gray-600 text-sm md:text-base">{{ area1.user ? area1.user.name :
										'Bilinmeyen Kullanıcı' }} - {{ area1.user ? area1.user.surname : '' }}</p>
								</div>
								<p class="text-gray-600 text-xs md:text-sm">{{ formatDate(area1.created_at) }}</p>
							</div>
						</div>
					</div>
				</a>
			</div>

			<!--Posts Container-->
			<div class="flex flex-wrap justify-between pt-12 -mx-6">

				<!-- Area2 -->
				<div class="w-full md:w-1/3 p-6 flex flex-col flex-grow flex-shrink" v-for="area2 in area2PostsData"
					:key="area2.id">
					<div class="flex-1 bg-white rounded-t rounded-b-none overflow-hidden shadow-lg">
						<p class="text-gray-600 text-xs md:text-sm">{{ formattedCategories(area2) }}</p>
						<img :src="area2.image_url || '/front/22.jpg'" class="h-64 w-full rounded-t pb-6">
						<p class="w-full text-gray-600 text-xs md:text-sm px-6">{{ area2.name }}</p>
						<div class="w-full font-bold text-xl text-gray-900 px-6">{{ area2.title }}</div>
						<p class="text-gray-800 font-serif text-base px-6 mb-5">
							{{ showFullContent ? area2.content : truncatedContent(area2.content) }}
						</p>
						<div class="flex justify-center w-full">
							<a :href="'/blog/' + area2.slug" v-if="!showFullContent" @click="showFullContent = true"
								class="devam-butonu text-center font-normal hover:font-bold">
								Devamını Oku
							</a>
						</div>
					</div>
					<div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-6">
						<div class="flex items-center justify-between">
							<div class="flex items-center space-x-2">
								<img class="w-8 h-8 rounded-full mr-4 avatar" data-tippy-content="Author Name"
									:src="area2.user && area2.user.avatar ? area2.user.avatar : 'http://i.pravatar.cc/300'"
									alt="Avatar">
								<p class="text-gray-600 text-sm md:text-base">{{ area2.user ? area2.user.name :
									'Bilinmeyen Kullanıcı' }}
									- {{ area2.user ? area2.user.surname : '' }}</p>
							</div>
							<p class="text-gray-600 text-xs md:text-sm">{{ formatDate(area2.created_at) }}</p>
						</div>
					</div>
				</div>

				<!--Area1Fnish-->

				<!-- Area3 -->
				<div class="w-full md:w-1/3 p-6 flex flex-col flex-grow flex-shrink" v-for="area3 in area3PostsData"
					:key="area3.id">
					<div class="flex-1 bg-white rounded-t rounded-b-none overflow-hidden shadow-lg flex flex-col">
						<p class="text-gray-600 text-xs md:text-sm">{{ formattedCategories(area3) }}</p>
						<img :src="area3.image_url || '/front/22.jpg'" class="h-64 w-full rounded-t pb-6">
						<p class="w-full text-gray-600 text-xs md:text-sm px-6">{{ area3.name }}</p>
						<div class="w-full font-bold text-xl text-gray-900 px-6">{{ area3.title }}</div>
						<p class="text-gray-800 font-serif text-base px-6 mb-5 whitespace-normal flex-grow">
							{{ showFullContent ? area3.content : truncatedContent(area3.content) }}
						</p>

						<div class="flex justify-center w-full mt-auto">
							<a :href="'/blog/' + area3.slug" v-if="!showFullContent" @click="showFullContent = true"
								class="devam-butonu text-center font-normal hover:font-bold">
								Devamını Oku
							</a>
						</div>
					</div>
					<div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-6">
						<div class="flex items-center justify-between">
							<div class="flex items-center space-x-2">
								<img class="w-8 h-8 rounded-full mr-4 avatar" data-tippy-content="Author Name"
									:src="area3.user && area3.user.avatar ? area3.user.avatar : 'http://i.pravatar.cc/300'"
									alt="Avatar">
								<p class="text-gray-600 text-sm md:text-base">{{ area3.user ? area3.user.name :
									'Bilinmeyen Kullanıcı' }} - {{ area3.user ? area3.user.surname : '' }}</p>
							</div>
							<p class="text-gray-600 text-xs md:text-sm">{{ formatDate(area3.created_at) }}</p>
						</div>
					</div>
				</div>


				<!-- Area4 -->
				<div class="w-full md:w-2/3 p-6 flex flex-col flex-grow flex-shrink" v-for="area4 in area4PostsData">
					<div class="flex-1 bg-white rounded-t rounded-b-none overflow-hidden shadow-lg flex flex-col">
						<p class="w-full text-gray-600 text-xs md:text-sm px-6">{{ formattedCategories(area4) }}</p>
						<div class="w-full font-bold text-xl text-gray-900 px-6">{{ area4.title }}</div>
						<p class="text-gray-800 font-serif text-base px-6 mb-5 flex-grow">
							{{ showFullContent ? area4.content : truncatedContent(area4.content) }}
						</p>
						<div class="flex justify-center w-full mt-auto">
							<a :href="'/blog/' + area4.slug" v-if="!showFullContent" @click="showFullContent = true"
								class="devam-butonu text-center font-normal hover:font-bold">
								Devamını Oku
							</a>
						</div>
					</div>
					<div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-6">
						<div class="flex items-center justify-between">
							<div class="flex items-center space-x-2">
								<p class="text-gray-600 text-sm md:text-base">{{ area4.user ? area4.user.name :
									'Bilinmeyen Kullanıcı' }}
									- {{ area4.user ? area4.user.surname : '' }}</p>
							</div>
							<p class="text-gray-600 text-xs md:text-sm">{{ formatDate(area4.created_at) }}</p>
						</div>
					</div>
				</div>


				<!-- Area5 -->
				<div class="w-full md:w-1/4 p-6 flex flex-col flex-grow flex-shrink" v-for="area5 in area5PostsData"
					:key="area5.id">

					<div class="flex-1 bg-white rounded-t rounded-b-none overflow-hidden shadow-lg">
						<p class="text-gray-600 text-xs md:text-sm px-6">{{ formattedCategories(area5) }}</p>
						<div class="w-full font-bold text-xl text-gray-900 px-6">{{ area5.title }}</div>
						<p class="text-gray-800 font-serif text-base px-6 mb-5">
							{{ showFullContent ? area5.content : truncatedContent(area5.content) }}
						</p>
					</div>

					<div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-6">
						<div class="flex justify-center w-full">
							<a :href="'/blog/' + area5.slug" v-if="!showFullContent" @click="showFullContent = true"
								class="devam-butonu text-center font-normal hover:font-bold">
								Devamını Oku
							</a>
						</div>
					</div>
					<div class="flex-none bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-6">
						<div class="flex items-center justify-between">
							<div class="flex items-center space-x-2">
								<img class="w-8 h-8 rounded-full mr-4 avatar" data-tippy-content="Author Name"
									:src="area5.user && area5.user.avatar ? area5.user.avatar : 'http://i.pravatar.cc/300'"
									alt="Avatar" />
								<p class="text-gray-600 text-sm md:text-base">
									{{ area5.user ? area5.user.name : 'Bilinmeyen Kullanıcı' }} - {{ area5.user ?
										area5.user.surname : '' }}
								</p>
							</div>
							<p class="text-gray-600 text-xs md:text-sm">{{ formatDate(area5.created_at) }}</p>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
</template>

<script>
import axios from "axios";

export default {
	name: "Home",
	data() {
		return {
			isLoggedIn: false,
			area1PostsData: [],
			area2PostsData: [],
			area3PostsData: [],
			area4PostsData: [],
			area5PostsData: [],
		};
	},
	mounted() {
		this.checkLoginStatus();
		this.area1Posts();
		this.area2Posts();
		this.area3Posts();
		this.area4Posts();
		this.area5Posts();
	},
	watch: {
		"$route": "checkLoginStatus",
	},
	computed: {
		formattedCategories() {
			return (post) => {
				if (!post.categories || post.categories.length === 0) {
					return "Genel";
				}
				return post.categories.map(cat => cat.name).join(" -> ");
			};
		}
	},
	methods: {
		checkLoginStatus() {
			this.isLoggedIn = !!localStorage.getItem("auth_token");
			if (this.$route.path === "/login" && this.isLoggedIn) {
				this.$router.push("/home");
			}
		},
		formatDate(date) {
			if (!date) return "";
			const d = new Date(date);
			return d.toLocaleDateString("tr-TR");
		}, truncatedContent(content) {
			const maxLength = 200; // Kısaltma uzunluğu
			return content.length > maxLength ? content.slice(0, maxLength) + "..." : content;
		},
		async area1Posts() {
			try {
				const response = await axios.get("http://localhost:8000/api/blogs/get/area1");
				this.area1PostsData = response.data;
			} catch (error) {
				console.error("Veri çekme hatası:", error);
			}
		},
		async area2Posts() {
			try {
				const response = await axios.get("http://localhost:8000/api/blogs/get/area2");
				this.area2PostsData = response.data;
			} catch (error) {
				console.error("Veri çekme hatası:", error);
			}
		},
		async area3Posts() {
			try {
				const response = await axios.get("http://localhost:8000/api/blogs/get/area3");
				this.area3PostsData = response.data;
			} catch (error) {
				console.error("Veri çekme hatası:", error);
			}
		},
		async area4Posts() {
			try {
				const response = await axios.get("http://localhost:8000/api/blogs/get/area4");
				this.area4PostsData = response.data;
			} catch (error) {
				console.error("Veri çekme hatası:", error);
			}
		},
		async area5Posts() {
			try {
				const response = await axios.get("http://localhost:8000/api/blogs/get/area5");
				this.area5PostsData = response.data;
			} catch (error) {
				console.error("Veri çekme hatası:", error);
			}
		},
	},
};

</script>
<style scoped>
.devam-butonu {
    display: inline-block;
    padding: 8px 16px;
    background-color: #3b82f6;
    color: white;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    font-size: 14px;
    text-align: center;
}

.devam-butonu:hover {
    font-weight: bold;
    background-color: #2563eb;
}
</style>
