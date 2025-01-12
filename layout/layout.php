<<<<<<< HEAD
<?php
session_start();
if(!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
	header("Location: ../page/login.php");
	exit;
}

$pageTitle = $pageTitle ?? 'WhatsApp Marketing';
$currentPage = $currentPage ?? '';
?>

<?php include 'header.php'; ?>

<div class="min-h-screen flex">
	<?php if(isset($_SESSION['user_id'])): ?>
		<!-- Mobile Menu Button -->
		<button id="mobile-menu-button" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-gray-800 text-white focus:outline-none">
			<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
			</svg>
		</button>

		<!-- Sidebar with mobile handling -->
		<div id="sidebar" class="transform lg:transform-none lg:opacity-100 lg:relative fixed inset-y-0 left-0 -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out z-40">
			<?php include 'sidebar.php'; ?>
		</div>

		<!-- Main Content -->
		<div class="flex-1 flex flex-col">
			<?php include 'navbar.php'; ?>
			<div class="p-4 lg:p-8 flex-1">
				<?php echo $content; ?>
			</div>
			<?php include 'footer.php'; ?>
		</div>

		<!-- Overlay for mobile -->
		<div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-30 hidden lg:hidden"></div>
	<?php else: ?>
		<div class="flex-1">
			<?php echo $content; ?>
		</div>
	<?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const mobileMenuButton = document.getElementById('mobile-menu-button');
	const sidebar = document.getElementById('sidebar');
	const overlay = document.getElementById('sidebar-overlay');

	function toggleSidebar() {
		sidebar.classList.toggle('-translate-x-full');
		overlay.classList.toggle('hidden');
		document.body.classList.toggle('overflow-hidden');
	}

	mobileMenuButton?.addEventListener('click', toggleSidebar);
	overlay?.addEventListener('click', toggleSidebar);

	// Close sidebar when clicking outside on mobile
	document.addEventListener('click', function(event) {
		const isClickInside = sidebar?.contains(event.target) || mobileMenuButton?.contains(event.target);
		if (!isClickInside && !sidebar?.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
			toggleSidebar();
		}
	});

	// Handle resize events
	window.addEventListener('resize', function() {
		if (window.innerWidth >= 1024) {
			sidebar?.classList.remove('-translate-x-full');
			overlay?.classList.add('hidden');
			document.body.classList.remove('overflow-hidden');
		}
	});
});
=======
<?php
session_start();
if(!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
	header("Location: ../page/login.php");
	exit;
}

$pageTitle = $pageTitle ?? 'WhatsApp Marketing';
$currentPage = $currentPage ?? '';
?>

<?php include 'header.php'; ?>

<div class="min-h-screen flex">
	<?php if(isset($_SESSION['user_id'])): ?>
		<!-- Mobile Menu Button -->
		<button id="mobile-menu-button" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-gray-800 text-white focus:outline-none">
			<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
			</svg>
		</button>

		<!-- Sidebar with mobile handling -->
		<div id="sidebar" class="transform lg:transform-none lg:opacity-100 lg:relative fixed inset-y-0 left-0 -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out z-40">
			<?php include 'sidebar.php'; ?>
		</div>

		<!-- Main Content -->
		<div class="flex-1 flex flex-col">
			<?php include 'navbar.php'; ?>
			<div class="p-4 lg:p-8 flex-1">
				<?php echo $content; ?>
			</div>
			<?php include 'footer.php'; ?>
		</div>

		<!-- Overlay for mobile -->
		<div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-30 hidden lg:hidden"></div>
	<?php else: ?>
		<div class="flex-1">
			<?php echo $content; ?>
		</div>
	<?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const mobileMenuButton = document.getElementById('mobile-menu-button');
	const sidebar = document.getElementById('sidebar');
	const overlay = document.getElementById('sidebar-overlay');

	function toggleSidebar() {
		sidebar.classList.toggle('-translate-x-full');
		overlay.classList.toggle('hidden');
		document.body.classList.toggle('overflow-hidden');
	}

	mobileMenuButton?.addEventListener('click', toggleSidebar);
	overlay?.addEventListener('click', toggleSidebar);

	// Close sidebar when clicking outside on mobile
	document.addEventListener('click', function(event) {
		const isClickInside = sidebar?.contains(event.target) || mobileMenuButton?.contains(event.target);
		if (!isClickInside && !sidebar?.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
			toggleSidebar();
		}
	});

	// Handle resize events
	window.addEventListener('resize', function() {
		if (window.innerWidth >= 1024) {
			sidebar?.classList.remove('-translate-x-full');
			overlay?.classList.add('hidden');
			document.body.classList.remove('overflow-hidden');
		}
	});
});
>>>>>>> 228c558 (updated)
</script>