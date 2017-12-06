<!DOCTYPE html>

<html>
	<head>
		<!-- meta -->
		<title>Collections</title>
		<link rel="icon" type="image/png" href="../img/favicon/favicon-16x16.png" sizes="16x16"/>
		<link rel="icon" type="image/png" href="../img/favicon/favicon-32x32.png" sizes="32x32"/>
		<!-- libraries -->
		<script type="text/javascript" src="../includes/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="//wow.zamimg.com/widgets/power.js"></script>
		<!-- style -->
		<link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
		<!-- scripts -->
		<script type="text/javascript" src="../scripts/helpers.js"></script>
		<script type="text/javascript" src="../scripts/view_main.js"></script>
		<script type="text/javascript" src="../scripts/msgBox.js"></script>
	</head>
	<body>
		<div id="background"></div>
		<div id="msgBoxWrap"><div id="msgBox"></div></div>
		<div class="headerBar main">
			<button class="optionButton main left" id="currentType" onclick="toggleNav('left')"><?= $_GET['type'] ?></button>
			<div class="headBox mid">
				<button class="mainCatButton prev" type="button" onclick="changeContent('prev')"><img src="../img/arrow3.png" class="arrowButtonImg"></button>
				<div id="mainCatTextWrap"><p id="mainCatText">AZEROTH</p></div>
				<button class="mainCatButton next" type="button" onclick="changeContent('next')"><img src="../img/arrow3.png" class="arrowButtonImg"></button>
			</div>
			<div id="progress">10 / 10</div>
			<!-- <button class='optionButton main right' id="depthButton" onclick='cycleDepth()'>DETAIL</button> -->
		</div>
		<div id="progBarBack">
			<div id="progBar"></div>
		</div>
		<div class="sideNav left main" id="leftNav">
			<button class="sideNavButton left" onclick="changeType('mounts')">MOUNTS</button>
			<button class="sideNavButton left" onclick="changeType('pets')">PETS</button>
			<button class="sideNavButton left" onclick="changeType('toys')">TOYS</button>
			<div class="sideNavDivider"></div>
			<button class="sideNavButton left" onclick="changeType('alchemy')">ALCHEMY</button>
			<button class="sideNavButton left" onclick="changeType('blacksmithing')">BLACKSMITHING</button>
			<button class="sideNavButton left" onclick="changeType('enchanting')">ENCHANTING</button>
			<button class="sideNavButton left" onclick="changeType('engineering')">ENGINEERING</button>
			<button class="sideNavButton left" onclick="changeType('inscription')">INSCRIPTION</button>
			<button class="sideNavButton left" onclick="changeType('jewelcrafting')">JEWELCRAFTING</button>
			<button class="sideNavButton left" onclick="changeType('leatherworking')">LEATHERWORKING</button>
			<button class="sideNavButton left" onclick="changeType('tailoring')">TAILORING</button>
			<div class="sideNavDivider"></div>
			<button class="sideNavButton left" onclick="changeType('cooking')">COOKING</button>
			<button class="sideNavButton left" onclick="changeType('firstaid')">FIRST AID</button>
			<button class="sideNavButton left" onclick="changeType('mining')">MINING</button>
		</div>
		<section id="filterWrap">
			<button class="filterButton" onclick="filter(this)">ALL</button>
			<button class="filterButton" onclick="filter(this)">ACHIEVEMENT</button>
			<button class="filterButton" onclick="filter(this)">DROP</button>
			<button class="filterButton" onclick="filter(this)">PROFESSION</button>
			<button class="filterButton" onclick="filter(this)">PVP</button>
			<button class="filterButton" onclick="filter(this)">QUEST</button>
			<button class="filterButton" onclick="filter(this)">REPUTATION</button>
			<button class="filterButton" onclick="filter(this)">VENDOR</button>
			<button class="filterButton" onclick="filter(this)">WORLD EVENT</button>
		</section>
		<div id="mainWrapper"></div>
	</body>
</html>
