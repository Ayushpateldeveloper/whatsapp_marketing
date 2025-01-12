<div class="bg-gray-800 shadow-lg">
	<div class="px-4 lg:px-8 py-4 flex items-center justify-between">
		<!-- Add margin-left for mobile to account for hamburger menu -->
		<h1 class="text-xl lg:text-2xl font-semibold ml-12 lg:ml-0">
			<?php 
			if ($currentPage === 'posts') {
				echo 'Manage Posts';
			} else {
				echo $pageTitle ?? 'Dashboard';
			}
			?>
		</h1>
		<div class="flex items-center space-x-4">
			<div class="flex items-center space-x-2">
				<div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-400 to-blue-500 flex items-center justify-center">
					<?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'U'; ?>
				</div>
				<span class="text-sm font-medium hidden sm:inline-block"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?></span>
			</div>
			<a href="../page/logout.php" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 transition-colors">
				Logout
			</a>
		</div>
	</div>
</div>