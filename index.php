<!DOCTYPE html>

<html>
	<head>
		<!-- meta -->
		<title>Completionism</title>
		<link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" sizes="16x16"/>
		<link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" sizes="32x32"/>
		<!-- libraries -->
		<script type="text/javascript" src="includes/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="//wow.zamimg.com/widgets/power.js"></script>
		<script>var wowhead_tooltips = { "colorlinks": false, "iconizelinks": false, "renamelinks": false }</script>
		<!-- style -->
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
		<!-- scripts -->
		<script type="text/javascript" src="../scripts/view_landing.js"></script>
		<script type="text/javascript" src="../scripts/msgBox.js"></script>
	</head>
	<body>
		<div id="background"></div>
		<div id="msgBox"></div>

		<div class="headerBar index">
			<button class="optionButton index left" id="currentType" onclick="toggleNav('left')">LINK</button>
			<button class="optionButton index right" id="currentType" onclick="location.assign('views/update.php')">UPDATE</button>
		</div>

		<div class="sideNav left index" id="leftNav">
			<div id="loginBox">
				<form id="linkForm" action="Javascript:ajax_pullData()" method="post">
					<select name="type">
						<option value="" selected>Select Category</option>
						<option value="mounts">Mounts</option>
						<option value="pets">Pets</option>
						<option value="alchemy">Alchemy</option>
						<option value="blacksmithing">Blacksmithing</option>
						<option value="enchanting">Enchanting</option>
						<option value="engineering">Engineering</option>
						<option value="inscription">Inscription</option>
						<option value="jewelcrafting">Jewelcrafting</option>
						<option value="leatherworking">Leatherworking</option>
						<option value="tailoring">Tailoring</option>
						<option value="cooking">Cooking</option>
						<option value="firstaid">First Aid</option>
						<option value="mining">Mining</option>
					</select>
					<select name="region" onchange="ajax_changeRegion(this.value, null)">
						<option value="" selected>Select Region</option>
						<option value="eu">EU</option>
						<option value="us">US</option>
					<select>
					<select name="realms">
						<option value="" selected>Select Realm</option>
					</select>
					<input type="text" name="char" placeholder="Character" autocomplete="off" value="">
					<input type="submit" value="LINK">
				</form>
			</div>
		</div>
		<div id="typeWrap">
			<div class="typeBox">
				<div class="typeRow">
					<button class="categoryLink left" id="mountsLink" onclick="selectCat('mounts')"></button>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('mounts')">MOUNTS</button>
						<div class="buttonProg">
							<div class="buttonProgFill mounts"></div>
						</div>
					</div>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('pets')">PETS</button>
						<div class="buttonProg">
							<div class="buttonProgFill pets"></div>
						</div>
					</div>
					<button class="categoryLink right" id="petsLink" onclick="selectCat('pets')"></button>
				</div>
				<div class="typeRow">
					<button class="categoryLink left bad" id="toysLink" onclick="location.assign('views/update.php')"></button>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('toys')">TOYS</button>
						<div class="buttonProg">
							<div class="buttonProgFill toys"></div>
						</div>
					</div>
					<div class="buttonWrap">
						<button class="categoryButton two notReady" onclick="loadPage('notReady')">HEIRLOOMS</button>
						<div class="buttonProg">
							<div class="buttonProgFill heirlooms"></div>
						</div>
					</div>
					<button class="categoryLink right bad" id="heirloomsLink" onclick="newMsg('No Armory information available.</br>Manual update coming soon!')"></button>
				</div>
			</div>
			<div class="typeBox">
				<div class="typeRow">
					<button class="categoryLink left" id="alchemyLink" onclick="selectCat('alchemy')"></button>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('alchemy')">ALCHEMY</button>
						<div class="buttonProg">
							<div class="buttonProgFill alchemy"></div>
						</div>
					</div>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('blacksmithing')">BLACKSMITHING</button>
						<div class="buttonProg">
							<div class="buttonProgFill blacksmithing"></div>
						</div>
					</div>
					<button class="categoryLink right" id="blacksmithingLink" onclick="selectCat('blacksmithing')"></button>
				</div>
				<div class="typeRow">
					<button class="categoryLink left" id="enchantingLink" onclick="selectCat('enchanting')"></button>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('enchanting')">ENCHANTING</button>
						<div class="buttonProg">
							<div class="buttonProgFill enchanting"></div>
						</div>
					</div>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('engineering')">ENGINEERING</button>
						<div class="buttonProg">
							<div class="buttonProgFill engineering"></div>
						</div>
					</div>
					<button class="categoryLink right" id="engineeringLink" onclick="selectCat('engineering')"></button>
				</div>
				<div class="typeRow">
					<button class="categoryLink left" id="jewelcraftingLink" onclick="selectCat('jewelcrafting')"></button>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('jewelcrafting')">JEWELCRAFTING</button>
						<div class="buttonProg">
							<div class="buttonProgFill jewelcrafting"></div>
						</div>
					</div>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('inscription')">INSCRIPTION</button>
						<div class="buttonProg">
							<div class="buttonProgFill inscription"></div>
						</div>
					</div>
					<button class="categoryLink right" id="inscriptionLink" onclick="selectCat('inscription')"></button>
				</div>
				<div class="typeRow">
					<button class="categoryLink left" id="leatherworkingLink" onclick="selectCat('leatherworking')"></button>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('leatherworking')">LEATHERWORKING</button>
						<div class="buttonProg">
							<div class="buttonProgFill leatherworking"></div>
						</div>
					</div>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('tailoring')">TAILORING</button>
						<div class="buttonProg">
							<div class="buttonProgFill tailoring"></div>
						</div>
					</div>
					<button class="categoryLink right" id="tailoringLink" onclick="selectCat('tailoring')"></button>
				</div>
			</div>
			<div class="typeBox">
				<div class="typeRow">
					<button class="categoryLink left bad" id="archaeologyLink" onclick="newMsg('No Armory information available.</br>Manual update coming soon!')"></button>
					<div class="buttonWrap">
						<button class="categoryButton two notReady" onclick="loadPage('notReady')">ARCHAEOLOGY</button>
						<div class="buttonProg">
							<div class="buttonProgFill archaeology"></div>
						</div>
					</div>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('cooking')">COOKING</button>
						<div class="buttonProg">
							<div class="buttonProgFill cooking"></div>
						</div>
					</div>
					<button class="categoryLink right" id="cookingLink" onclick="selectCat('cooking')"></button>
				</div>
				<div class="typeRow">
					<button class="categoryLink left" id="firstaidLink" onclick="selectCat('firstaid')"></button>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('firstaid')">FIRST AID</button>
						<div class="buttonProg">
							<div class="buttonProgFill firstaid"></div>
						</div>
					</div>
					<div class="buttonWrap">
						<button class="categoryButton two ready" onclick="loadPage('mining')">MINING</button>
						<div class="buttonProg">
							<div class="buttonProgFill mining"></div>
						</div>
					</div>
					<button class="categoryLink right" id="miningLink" onclick="selectCat('mining')"></button>
				</div>
			</div>
		</div>
	</body>
</html>
