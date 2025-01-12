<?php 
$pageTitle = 'Create Post';
$currentPage = 'posts';
ob_clean();

require_once '../backend/database.php';

// Get existing posts from database
$posts = [];
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result) {
	while($row = $result->fetch_assoc()) {
		$posts[] = $row;
	}
}
ob_start();
?>

<div class="min-h-screen bg-gray-800 p-2 sm:p-4 md:p-6">
	<section class="mb-4 sm:mb-6">
		<div class="max-w-7xl mx-auto px-2 sm:px-4">
			<div class="mb-4">
				<div class="w-full">
					<h2 class="text-lg sm:text-xl md:text-2xl font-bold text-white">Manage Posts</h2>
				</div>
			</div>
		</div>
	</section>

	<section class="mb-4 sm:mb-6">
		<div class="max-w-7xl mx-auto px-2 sm:px-4">
			<div class="flex flex-col lg:flex-row gap-4">
				<!-- Form Column -->
				<div class="w-full lg:w-5/12">
					<div class="bg-gray-800 rounded-xl shadow-lg">
						<div class="border-b border-gray-700 p-3 sm:p-4">
							<h3 class="text-base sm:text-lg font-semibold text-white" id="formTitle">Create New Post</h3>
						</div>
						<div class="p-3 sm:p-4">
							<form id="postForm" enctype="multipart/form-data">
								<input type="hidden" id="postId" name="postId">
								<div class="mb-4">
									<label for="message" class="block text-sm font-medium text-gray-300 mb-2">Message Text</label>
									<textarea class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" id="message" name="message" rows="4" required></textarea>
								</div>

								<div class="mb-4">
									<label for="mediaFile" class="block text-sm font-medium text-gray-300 mb-2">Media File (Image/Video/Audio)</label>
									<div class="relative">
										<input type="file" class="hidden" id="mediaFile" name="mediaFile" accept="image/*,video/*,audio/*">
										<label for="mediaFile" class="cursor-pointer inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm sm:text-base">
											Choose file
										</label>
									</div>
								</div>

								<div class="mb-4">
									<label class="block text-sm font-medium text-gray-300 mb-2">Preview</label>
									<div id="mediaPreview" class="mt-2"></div>
								</div>

								<div class="flex gap-2">
									<button type="submit" class="px-3 py-2 sm:px-4 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base" id="submitBtn">Create Post</button>
									<button type="button" class="hidden px-3 py-2 sm:px-4 sm:py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm sm:text-base" id="resetBtn">Cancel</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- List Column -->
				<div class="w-full lg:w-7/12">
					<div class="bg-gray-800 rounded-xl shadow-lg">
						<div class="border-b border-gray-700 p-3 sm:p-4">
							<h3 class="text-base sm:text-lg font-semibold text-white">Posts List</h3>
						</div>
						<div class="p-3 sm:p-4">
							<div class="overflow-x-auto">
								<table class="min-w-full divide-y divide-gray-700">
									<thead class="bg-gray-700">
										<tr>
											<th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-300 uppercase tracking-wider">Message</th>
											<th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-300 uppercase tracking-wider hidden sm:table-cell">Media</th>
											<th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-300 uppercase tracking-wider hidden md:table-cell">Created At</th>
											<th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-300 uppercase tracking-wider">Actions</th>
										</tr>
									</thead>
									<tbody class="divide-y divide-gray-700">
										<?php foreach($posts as $post): ?>
										<tr class="hover:bg-gray-700 transition-colors">
											<td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-normal text-sm text-white">
												<div class="line-clamp-2 sm:line-clamp-none"><?php echo htmlspecialchars($post['message']); ?></div>
												<?php if($post['media_url']): ?>
													<a href="<?php echo htmlspecialchars($post['media_url']); ?>" target="_blank" class="text-blue-400 hover:text-blue-300 sm:hidden block mt-1">View Media</a>
												<?php endif; ?>
											</td>
											<td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap hidden sm:table-cell">
												<?php if($post['media_url']): ?>
													<a href="<?php echo htmlspecialchars($post['media_url']); ?>" target="_blank" class="text-blue-400 hover:text-blue-300">View Media</a>
												<?php endif; ?>
											</td>
											<td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-white hidden md:table-cell">
												<?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?>
											</td>
											<td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm">
												<div class="flex space-x-2">
													<button class="p-1.5 sm:p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors edit-post" data-id="<?php echo $post['id']; ?>" 
														data-message="<?php echo htmlspecialchars($post['message']); ?>"
														data-media="<?php echo htmlspecialchars($post['media_url']); ?>"
														data-media-type="<?php echo htmlspecialchars($post['media_type']); ?>">
														<i class="fas fa-edit text-xs sm:text-sm"></i>
													</button>
													<button class="p-1.5 sm:p-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors delete-post" data-id="<?php echo $post['id']; ?>">
														<i class="fas fa-trash text-xs sm:text-sm"></i>
													</button>
												</div>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
$(document).ready(function() {
	// Existing file input handler code remains the same
	$('#mediaFile').change(function(e) {
		var file = e.target.files[0];
		var reader = new FileReader();
		var preview = $('#mediaPreview');
		
		$('.custom-file-label').text(file.name);
		
		reader.onload = function(e) {
			preview.empty();
			if (file.type.startsWith('image/')) {
				preview.html(`<img src="${e.target.result}" class="max-h-[300px] w-auto">`);
			} else if (file.type.startsWith('video/')) {
				preview.html(`<video controls class="max-h-[300px] w-auto">
					<source src="${e.target.result}" type="${file.type}">
					Your browser does not support the video tag.
				</video>`);
			} else if (file.type.startsWith('audio/')) {
				preview.html(`<audio controls>
					<source src="${e.target.result}" type="${file.type}">
					Your browser does not support the audio tag.
				</audio>`);
			}
		};
		
		reader.readAsDataURL(file);
	});

	// Handle form submission
	$('#postForm').submit(function(e) {
		e.preventDefault();
		
		var formData = new FormData(this);
		var postId = $('#postId').val();
		var url = postId ? '../backend/update_post.php' : '../backend/create_post.php';
		
		$.ajax({
			url: url,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				if (response.success) {
					toastr.success(postId ? 'Post updated successfully' : 'Post created successfully');
					resetForm();
					location.reload(); // Reload to update the list
				} else {
					toastr.error(response.message || 'Error processing post');
				}
			},
			error: function() {
				toastr.error('Error processing post');
			}
		});
	});

	// Edit post
	$('.edit-post').click(function() {
		var id = $(this).data('id');
		var message = $(this).data('message');
		var mediaUrl = $(this).data('media');
		
		$('#postId').val(id);
		$('#message').val(message);
		$('#formTitle').text('Edit Post');
		$('#submitBtn').text('Update Post');
		$('#resetBtn').show();
		
		// Show existing media if available
		if (mediaUrl) {
			showExistingMedia(mediaUrl);
		} else {
			$('#mediaPreview').empty();
		}
		
		// Scroll to form
		$('html, body').animate({
			scrollTop: $("#postForm").offset().top - 100
		}, 500);
	});

	// Function to handle existing media display
	function showExistingMedia(mediaUrl) {
		var preview = $('#mediaPreview');
		preview.empty();

		// Check file type based on extension
		if (mediaUrl.match(/\.(jpg|jpeg|png|gif)$/i)) {
			preview.html(`<img src="${mediaUrl}" class="max-h-[300px] w-auto mb-2"><p class="text-sm text-gray-300">Current media file</p>`);
		} else if (mediaUrl.match(/\.(mp4|webm|ogg)$/i)) {
			preview.html(`<video controls class="max-h-[300px] w-auto mb-2">
				<source src="${mediaUrl}" type="video/mp4">
				Your browser does not support the video tag.
			</video><p class="text-sm text-gray-300">Current media file</p>`);
		} else if (mediaUrl.match(/\.(mp3|wav)$/i)) {
			preview.html(`<audio controls class="mb-2">
				<source src="${mediaUrl}" type="audio/mpeg">
				Your browser does not support the audio tag.
			</audio><p class="text-sm text-gray-300">Current media file</p>`);
		}
	}

	// Delete post
	$('.delete-post').click(function() {
		var id = $(this).data('id');
		
		if (confirm('Are you sure you want to delete this post?')) {
			$.ajax({
				url: '../backend/delete_post.php',
				type: 'POST',
				data: { id: id },
				success: function(response) {
					if (response.success) {
						toastr.success('Post deleted successfully');
						location.reload();
					} else {
						toastr.error(response.message || 'Error deleting post');
					}
				},
				error: function() {
					toastr.error('Error deleting post');
				}
			});
		}
	});

	// Reset form
	$('#resetBtn').click(function() {
		resetForm();
	});

	function resetForm() {
		$('#postForm')[0].reset();
		$('#postId').val('');
		$('#mediaPreview').empty();
		$('.custom-file-label').text('Choose file');
		$('#formTitle').text('Create New Post');
		$('#submitBtn').text('Create Post');
		$('#resetBtn').hide();
	}
});
</script>

<?php
$content = ob_get_clean();
require_once '../layout/layout.php';
?>