<?php
// Optional logic for $stextval
if (strcmp($this->params['_pageType'], "home")) {
	$searchQuery = null;
	if (strcmp($this->params['_pageType'], "search") == 0) {
		$searchQuery = $this->params['_searchQuery'];
	}
	if (isset($searchQuery)) {
		$stextval = $searchQuery;
	} else {
		$stextval = "";
	}
}
?>

<div id="search">
	<div id="replacewithmobileordesktop"></div>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
			const container = document.getElementById("replacewithmobileordesktop");

			// Check screen size; you could also check navigator.userAgent.
			function loadContent() {
				// If width <= 600px, treat as "mobile"; otherwise "desktop"
				if (window.matchMedia("(max-width: 600px)").matches) {
					container.innerHTML = `
				<!-- MOBILE COMPONENTS -->
				<div id="search-controls">
					<button type="button" id="filterBtn" class="filter-btn">
						<span class="filter-btn-content">
							<img 
							src="https://imgstore.org/icon/69jia65cyaso/ffffff/128" 
							class="filter-icon" 
							id="filterIcon"
							referrerpolicy="unsafe-url"
							/>
							Filter
						</span>
					</button>
					<div style="float: right;">
						<a class="searchtipslink">Search Tips</a>
					</div>
				</div>
				<div id="searchbar">
					<form name="searchform" action="/search/" method="get" id="searchform">
						<input type="text" class="searchquery" name="q" placeholder="Search …" />
						<input type="submit" class="searchsubmit" value="l" />
					</form>
				</div>
			`;
				} else {
					container.innerHTML = `
				<!-- DESKTOP COMPONENTS -->
				<button type="button" id="filterBtn" class="filter-btn">
					<span class="filter-btn-content">
						<img 
						src="https://imgstore.org/icon/69jia65cyaso/ffffff/128" 
						class="filter-icon" 
						id="filterIcon"
						referrerpolicy="unsafe-url"
						/>
						Filter
					</span>
				</button>
				<a class="searchtipslink">Search Tips</a>
				<div id="searchbar">
					<form name="searchform" action="/search/" method="get" id="searchform">
						<input type="text" class="searchquery" name="q" placeholder="Search …" />
						<input type="submit" class="searchsubmit" value="l" />
					</form>
				</div>
			`;
				}
			}

			// Run at load
			loadContent();
			// Also reload on resize if you want dynamic switching
			window.addEventListener("resize", loadContent);
		});
	</script>

	<!-- Modal for selecting collections -->
	<div id="filterModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<h2>Select Collections</h2>

			<div id="collectionChips">
				<!-- Example chip structure -->
				<div class="chip" data-value="bukhari">Sahih Bukhari</div>
				<div class="chip" data-value="muslim">Sahih Muslim</div>
				<div class="chip" data-value="abudawud">Sunan Abi Dawud</div>
				<div class="chip" data-value="tirmidhi">Jami' at-Tirmidhi</div>
				<div class="chip" data-value="nasai">Sunan an-Nasa'i</div>
				<div class="chip" data-value="ibnmajah">Sunan Ibn Majah</div>
				<!-- etc... -->
			</div>

			<button id="applyFilterBtn" class="apply-filter-btn">Apply</button>
		</div>
	</div>

	<div id="searchtips">
		<div class="clear"></div>
		<b>Quotes</b> e.g. "pledge allegiance"<br>
		Searches for the whole phrase instead of individual words
		<p>
			<b>Wildcards</b> e.g. test*<br>
			Matches any set of one or more characters. For example test* would result in test, tester, testers, etc.
		<p>
			<b>Fuzzy Search</b> e.g. swore~<br>
			Finds terms that are similar in spelling. For example swore~ would result in swore, snore, score, etc.
		<p>
			<b>Term Boosting</b> e.g. pledge^4 hijrah<br>
			Boosts words with higher relevance. Here, the word <i>pledge</i> will have higher weight than <i>hijrah</i>
		<p>
			<b>Boolean Operators</b> e.g. ("pledge allegiance" OR "shelter) AND prayer<br>
			Create complex phrase and word queries by using Boolean logic.
		<p>
			<a href="/searchtips">More ...</a>
		<div class="clear"></div>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		const filterBtn = document.getElementById("filterBtn");
		const filterModal = document.getElementById("filterModal");
		const closeModal = document.querySelector("#filterModal .close");
		const applyFilterBtn = document.getElementById("applyFilterBtn");
		const chipElements = document.querySelectorAll("#collectionChips .chip");
		const searchForm = document.getElementById("searchform");
		const filterIcon = document.getElementById("filterIcon");

		let selectedCollections = [];

		// Open modal
		filterBtn.addEventListener("click", function () {
			filterModal.style.display = "block";
		});

		// Close modal (x)
		closeModal.addEventListener("click", function () {
			filterModal.style.display = "none";
		});

		// Close modal if clicking outside
		window.addEventListener("click", function (event) {
			if (event.target === filterModal) {
				filterModal.style.display = "none";
			}
		});

		// Toggle chip selection
		chipElements.forEach(chip => {
			chip.addEventListener("click", function () {
				const value = chip.dataset.value;
				chip.classList.toggle("selected");

				if (chip.classList.contains("selected")) {
					selectedCollections.push(value);
				} else {
					selectedCollections = selectedCollections.filter(col => col !== value);
				}
			});
		});

		// "Apply" button
		applyFilterBtn.addEventListener("click", function () {
			filterModal.style.display = "none";
		});

		// Intercept search form to include selected collections
		searchForm.addEventListener("submit", function (event) {
			const queryInput = document.querySelector(".searchquery").value;
			let actionUrl = "/search/?q=" + encodeURIComponent(queryInput);

			selectedCollections.forEach(col => {
				actionUrl += "&collection[]=" + encodeURIComponent(col);
			});

			window.location.href = actionUrl;
			event.preventDefault();
		});
	});
</script>

<style>
	body {
		--secondary-block-bg: #ebebeb;
		--highlight-color: #3ba08f;
		--chip-bg: #eaeaea;
		--chip-hover-bg: #dedede;
		--chip-selected-bg: #3ba08f;
		--border-color: rgba(0, 0, 0, 0.2);
		--filter-icon-color: #333;
	}

	body[data-theme="dark"] {
		--secondary-block-bg: #343A40;
		--highlight-color: #3ba08f;
		--chip-bg: #343A40;
		--chip-hover-bg: #3d4648;
		--chip-selected-bg: #3ba08f;
		--border-color: rgba(255, 255, 255, 0.2);
		--filter-icon-color: #ffffff;
	}

	.clear {
		clear: both;
	}



	/* Filter button */
	.filter-btn {
		float: left;
		margin-right: 10px;
		padding: 6px 12px;
		cursor: pointer;
		background-color: var(--highlight-color);
		color: var(--primary-text-color);
		border: none;
		border-radius: 10px;
		font-family: "Akzidenz Roman", Arial, sans-serif;
	}

	.filter-btn:hover {
		background-color: rgba(59, 160, 143, 0.85);
	}

	.filter-btn-content {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.filter-icon {
		width: 20px;
		height: 20px;
		margin-right: 8px;
	}

	/* Modal */
	.modal {
		display: none;
		position: fixed;
		z-index: 999;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgba(0, 0, 0, 0.4);
	}

	.modal-content {
		background-color: var(--secondary-block-bg);
		color: var(--primary-text-color);
		margin: 100px auto;
		padding: 20px;
		border: 1px solid var(--border-color);
		width: 400px;
		/* Default for desktops */
		position: relative;
		border-radius: 8px;
	}

	.modal-content .close {
		position: absolute;
		top: 10px;
		right: 10px;
		color: var(--secondary-text-color);
		font-size: 24px;
		font-weight: bold;
		cursor: pointer;
	}

	.modal-content .close:hover {
		color: var(--primary-text-color);
	}

	#collectionChips {
		margin: 20px 0;
	}

	.chip {
		display: inline-block;
		padding: 8px 12px;
		margin: 4px;
		background-color: var(--chip-bg);
		color: var(--primary-text-color);
		border-radius: 16px;
		cursor: pointer;
		user-select: none;
		font-size: 13px;
		transition: background-color 0.2s ease;
	}

	.chip:hover {
		background-color: var(--chip-hover-bg);
	}

	.chip.selected {
		background-color: var(--chip-selected-bg);
		color: #fff;
	}

	.apply-filter-btn {
		margin-top: 10px;
		padding: 6px 12px;
		cursor: pointer;
		background-color: var(--highlight-color);
		color: var(--primary-text-color);
		border: none;
		border-radius: 6px;
		font-family: "Akzidenz Roman", Arial, sans-serif;
	}

	.apply-filter-btn:hover {
		background-color: rgba(59, 160, 143, 0.85);
	}

	/* 
	  MEDIA QUERIES FOR RESPONSIVENESS
	  Adjust the breakpoint (600px) as needed.
	*/
	/* 
	  MEDIA QUERIES FOR RESPONSIVENESS
	  Adjust the breakpoint (600px) as needed.
	*/
	@media screen and (max-width: 600px) {

		/* Make the modal content narrower so it fits the screen better */
		.modal-content {
			width: 90%;
			margin: 80px auto;
		}

		#replacewithmobileordesktop {
			width: 100%;
			margin: 0;
			padding: 0;
		}

		/* Adjust the #search container: stack items vertically */
		#search {
			display: flex;
			flex-wrap: wrap;
			align-items: flex-start;
		}

		#search-controls {
			width: 100%;
			display: flex;
			align-items: center;
			/* Vertical alignment */
			justify-content: space-between;
			/* Space between filter button and search tips */
			margin-bottom: 8px;
		}

		#search-controls .filter-btn {
			width: auto;
			/* Let the button take natural width */
			margin: 0;
			/* Remove default margins */
		}

		#search-controls .searchtipslink {
			margin: 0;
			/* Remove any default margins */
		}

		/* Force filter button to go full width if desired */
		.filter-btn {
			float: none;
			width: 30%;
			margin-right: 0;
			margin-bottom: 10px;
			justify-content: center;
			/* Center horizontally */
			align-items: center;
		}

		/* Make the searchbar also take full width */
		#searchbar {
			width: 100%;
		}

		/* Inside the form, stack the input and submit button if needed */
		#searchbar form {
			display: flex;
			flex-wrap: wrap;
			width: 100%;
		}

		.searchquery {
			flex: 1 1 100%;
			margin-bottom: 10px;
		}

		.searchsubmit {
			width: 10%;
			/* Or keep a smaller width: e.g. 50px or so, 
	   but center it or place it next to input gracefully */
		}

		/* Make chips wrap nicely */
		.chip {
			margin: 4px 4px;
		}
	}
</style>