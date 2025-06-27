<?php
  session_start();

  require_once 'api/config.php';

  if(!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New Article - JW Creative</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/article.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/all.min.css" />
    <!-- TinyMCE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0/tinymce.min.js"></script>
    <!-- Add Article CSS -->
    <link rel="stylesheet" href="css/add-article.css" />
  </head>
  <body>
    <div class="container">
      <!-- Sidebar -->
      <aside class="sidebar" id="sidebar">
        <div class="profile-section">
          <div class="profile-image">JW</div>
          <h2 class="profile-name">Jordan Wheeler</h2>
          <p class="profile-title">Full Stack Developer</p>
        </div>

        <nav>
          <ul class="nav-menu">
            <li class="nav-item">
              <a href="index.html" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
              <a href="index.html#about" class="nav-link">About</a>
            </li>
            <li class="nav-item">
              <a href="index.html#projects" class="nav-link">Projects</a>
            </li>
            <li class="nav-item">
              <a href="blog.html" class="nav-link">Blog</a>
            </li>
            <li class="nav-item">
              <a href="add-article.html" class="nav-link active">Add Article</a>
            </li>
            <li class="nav-item">
              <a href="index.html#contact" class="nav-link">Contact</a>
            </li>
          </ul>
        </nav>

        <div class="contact-info">
          <div class="contact-item">
            <div class="contact-icon">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <span>hello@jwcreative.ca</span>
          </div>
          <div class="contact-item">
            <div class="contact-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <span>Newfoundland, Canada</span>
          </div>

          <div class="social-links">
            <a href="#" class="social-link">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="#" class="social-link">
              <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="#" class="social-link">
              <i class="fa-brands fa-x"></i>
            </a>
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="main-content">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Add Article Section -->
        <section class="post-section">
          <div class="post-container">
            <!-- Back Button -->
            <a href="blog.html" class="back-button">
              <span>←</span>
              <span>Back to Blog</span>
            </a>

            <!-- Page Header -->
            <header class="post-header">
              <span class="post-category">Content Management</span>
              <h1 class="post-title">Add New Article</h1>
              <p class="post-description">
                Create and publish a new blog article with rich content
                formatting
              </p>
            </header>

            <!-- Success/Error Messages -->
            <div
              id="success-message"
              class="success-message"
              style="display: none"
            ></div>
            <div
              id="error-message"
              class="error-message"
              style="display: none"
            ></div>

            <!-- Article Form -->
            <div id="form-mode" class="form-container">
              <form id="article-form" novalidate>
                <div class="form-row">
                  <div class="form-group">
                    <label for="title">Article Title *</label>
                    <input
                      type="text"
                      id="title"
                      name="title"
                      placeholder="Enter article title"
                    />
                    <div class="validation-error" id="title-error"></div>
                  </div>
                  <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category">
                      <option value="">Select a category</option>
                      <option value="Technology">Technology</option>
                      <option value="Web Development">Web Development</option>
                      <option value="Programming">Programming</option>
                      <option value="Design">Design</option>
                      <option value="Tutorial">Tutorial</option>
                      <option value="Personal">Personal</option>
                      <option value="Other">Other</option>
                    </select>
                    <div class="validation-error" id="category-error"></div>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="author">Author *</label>
                    <input
                      type="text"
                      id="author"
                      name="author"
                      placeholder="Author name"
                      value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>"
                    />
                    <div class="validation-error" id="author-error"></div>
                  </div>
                  <div class="form-group">
                    <label for="tags">Tags</label>
                    <input
                      type="text"
                      id="tags"
                      name="tags"
                      placeholder="e.g., javascript, react, tutorial (comma-separated)"
                    />
                  </div>
                </div>

                <div class="form-group">
                  <label for="excerpt">Excerpt (Optional)</label>
                  <textarea
                    id="excerpt"
                    name="excerpt"
                    rows="3"
                    placeholder="Brief description of the article (auto-generated if left empty)"
                  ></textarea>
                </div>

                <div class="form-group tinymce-container">
                  <label for="content">Article Content *</label>
                  <textarea id="content" name="content"></textarea>
                  <div class="validation-error" id="content-error"></div>
                </div>

                <div class="form-actions">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    onclick="togglePreview()"
                  >
                    <i class="fas fa-eye"></i>
                    Preview
                  </button>
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Publish Article
                  </button>
                </div>
              </form>
            </div>

            <!-- Preview Mode -->
            <div id="preview-mode" class="preview-mode">
              <div class="preview-content">
                <div class="preview-header">
                  <span class="preview-category" id="preview-category"
                    >Category</span
                  >
                  <h1 class="preview-title" id="preview-title">
                    Article Title
                  </h1>
                  <div class="preview-meta">
                    <div class="meta-item">
                      <span>📅</span>
                      <span id="preview-date">Today</span>
                    </div>
                    <div class="meta-item">
                      <span>⏱️</span>
                      <span id="preview-read-time">5 min read</span>
                    </div>
                    <div class="meta-item">
                      <span>✍️</span>
                      <span id="preview-author">Author</span>
                    </div>
                  </div>
                  <p class="preview-description" id="preview-description">
                    Article description will appear here
                  </p>
                </div>

                <div class="post-content">
                  <div id="preview-content"></div>
                  <div class="preview-tags" id="preview-tags"></div>
                </div>
              </div>

              <div class="form-actions">
                <button
                  type="button"
                  class="btn btn-secondary"
                  onclick="togglePreview()"
                >
                  <i class="fas fa-edit"></i>
                  Edit
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  onclick="submitArticle()"
                >
                  <i class="fas fa-plus"></i>
                  Publish Article
                </button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    
<!-- Add this HTML to your page body -->
<div id="draft-modal" class="draft-modal">
  <div class="draft-modal-content">
    <div class="draft-modal-header">
      <div class="draft-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <polyline points="10,9 9,9 8,9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <h3>Draft Found</h3>
    </div>
    
    <div class="draft-modal-body">
      <p class="draft-description">We found a saved draft of your work. Would you like to continue where you left off?</p>
      
      <div class="draft-details">
        <div class="draft-detail-item">
          <span class="draft-detail-label">Title:</span>
          <span class="draft-detail-value" id="draft-title">Untitled Draft</span>
        </div>
        <div class="draft-detail-item">
          <span class="draft-detail-label">Last saved:</span>
          <span class="draft-detail-value" id="draft-time">Unknown</span>
        </div>
        <div class="draft-detail-item">
          <span class="draft-detail-label">Content:</span>
          <span class="draft-detail-value" id="draft-words">0 words</span>
        </div>
      </div>
    </div>
    
    <div class="draft-modal-actions">
      <button type="button" class="btn-secondary" onclick="discardDraft()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <polyline points="3,6 5,6 21,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Discard Draft
      </button>
      <button type="button" class="btn-primary" onclick="loadDraft()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <polyline points="7,10 12,15 17,10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Load Draft
      </button>
    </div>
  </div>
</div>

<!-- Auto-save indicator -->
<div id="autosave-indicator" class="autosave-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <polyline points="7,3 7,8 15,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  <span>Draft saved</span>
</div>

    <script>
      let isPreviewMode = false;
      let tinyMCEInitialized = false;
      let isPublished = false;

      // Initialize TinyMCE
      tinymce.init({
        selector: "#content",
        height: 500,
        menubar: false,
        plugins: [
          "advlist",
          "autolink",
          "lists",
          "link",
          "image",
          "charmap",
          "preview",
          "anchor",
          "searchreplace",
          "visualblocks",
          "code",
          "fullscreen",
          "insertdatetime",
          "media",
          "table",
          "help",
          "wordcount",
          "codesample",
        ],
        toolbar:
          "undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media | codesample | code preview fullscreen | help",
        content_style: `
    body {
      font-family: Inter, -apple-system, BlinkMacSystemFont, sans-serif;
      font-size: 16px;
      line-height: 1.6;
      color: #333;
      background: #fff;
    }
    pre {
      background: #f4f4f4;
      padding: 1rem;
      border-radius: 4px;
      overflow-x: auto;
    }
    blockquote {
      border-left: 4px solid #00d4ff;
      margin: 1rem 0;
      padding: 1rem 2rem;
      background: #f8f9fa;
    }
  `,
        skin: "oxide-dark",
        content_css: "dark",
        codesample_languages: [
          { text: "HTML/XML", value: "markup" },
          { text: "JavaScript", value: "javascript" },
          { text: "CSS", value: "css" },
          { text: "PHP", value: "php" },
          { text: "Python", value: "python" },
          { text: "Java", value: "java" },
          { text: "C#", value: "csharp" },
          { text: "C++", value: "cpp" },
          { text: "SQL", value: "sql" },
          { text: "JSON", value: "json" },
        ],
        setup: function (editor) {
          editor.on("init", function () {
            tinyMCEInitialized = true;
          });
          editor.on("input", autoSave);
        },
      });

      // Draft Modal Functions
      function showDraftModal() {
        const modal = document.getElementById("draft-modal");
        if (modal) {
          modal.style.display = "flex";
          document.body.style.overflow = "hidden";

          // Add animation
          setTimeout(() => {
            modal.classList.add("active");
          }, 10);
        }
      }

      function hideDraftModal() {
        const modal = document.getElementById("draft-modal");
        if (modal) {
          modal.classList.remove("active");
          document.body.style.overflow = "";

          setTimeout(() => {
            modal.style.display = "none";
          }, 300);
        }
      }

      function loadDraft() {
        const draft = localStorage.getItem("article_draft");
        if (draft) {
          try {
            const data = JSON.parse(draft);
            document.getElementById("title").value = data.title || "";
            document.getElementById("category").value = data.category || "";
            document.getElementById("author").value =
              data.author || "Jordan Wheeler";
            document.getElementById("tags").value = data.tags || "";
            document.getElementById("excerpt").value = data.excerpt || "";

            // Wait for TinyMCE to initialize
            const checkTinyMCE = setInterval(() => {
              if (tinymce.get("content")) {
                tinymce.get("content").setContent(data.content || "");
                clearInterval(checkTinyMCE);
              }
            }, 100);

            showSuccess("Draft loaded successfully!");
          } catch (e) {
            console.error("Error loading draft:", e);
            showError("Failed to load draft.");
          }
        }
        hideDraftModal();
      }

      function discardDraft() {
        localStorage.removeItem("article_draft");
        hideDraftModal();
      }

      function getDraftInfo() {
        const draft = localStorage.getItem("article_draft");
        if (!draft) return null;

        try {
          const data = JSON.parse(draft);
          const title = data.title || "Untitled Draft";
          const lastSaved = localStorage.getItem("article_draft_timestamp");
          const timeAgo = lastSaved
            ? formatTimeAgo(parseInt(lastSaved))
            : "Unknown";
          const wordCount = data.content
            ? data.content.replace(/<[^>]*>/g, "").split(/\s+/).length
            : 0;

          return { title, timeAgo, wordCount };
        } catch (e) {
          return null;
        }
      }

      function formatTimeAgo(timestamp) {
        const now = Date.now();
        const diff = now - timestamp;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return "Just now";
        if (minutes < 60)
          return `${minutes} minute${minutes > 1 ? "s" : ""} ago`;
        if (hours < 24) return `${hours} hour${hours > 1 ? "s" : ""} ago`;
        return `${days} day${days > 1 ? "s" : ""} ago`;
      }

      // Custom validation function
      function validateForm() {
        const errors = {};
        let isValid = true;

        // Clear previous errors
        document.querySelectorAll(".validation-error").forEach((el) => {
          el.style.display = "none";
          el.textContent = "";
        });

        // Validate title
        const title = document.getElementById("title").value.trim();
        if (!title) {
          errors.title = "Article title is required";
          isValid = false;
        }

        // Validate category
        const category = document.getElementById("category").value;
        if (!category) {
          errors.category = "Please select a category";
          isValid = false;
        }

        // Validate author
        const author = document.getElementById("author").value.trim();
        if (!author) {
          errors.author = "Author name is required";
          isValid = false;
        }

        // Validate content (TinyMCE)
        let content = "";
        if (tinyMCEInitialized && tinymce.get("content")) {
          content = tinymce.get("content").getContent().trim();
        }
        if (!content) {
          errors.content = "Article content is required";
          isValid = false;
        }

        // Display errors
        Object.keys(errors).forEach((field) => {
          const errorElement = document.getElementById(`${field}-error`);
          if (errorElement) {
            errorElement.textContent = errors[field];
            errorElement.style.display = "block";
          }
        });

        return isValid;
      }

      // Form submission
      document
        .getElementById("article-form")
        .addEventListener("submit", function (e) {
          e.preventDefault();
          submitArticle();
        });

      function submitArticle() {
  // Custom validation instead of HTML5 validation
  if (!validateForm()) {
    showError("Please fix the validation errors before submitting.");
    return;
  }

  const formData = new FormData();

  // Get form values
  formData.append("title", document.getElementById("title").value.trim());
  formData.append("category", document.getElementById("category").value);
  formData.append("author", document.getElementById("author").value.trim());
  formData.append("tags", document.getElementById("tags").value.trim());
  formData.append("excerpt", document.getElementById("excerpt").value.trim());

  // Get TinyMCE content
  if (tinyMCEInitialized && tinymce.get("content")) {
    formData.append("content", tinymce.get("content").getContent());
  } else {
    showError("Editor is not ready. Please wait a moment and try again.");
    return;
  }

  // Show loading state
  const submitBtn = document.querySelector(".btn-primary");
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publishing...';
  submitBtn.disabled = true;

  // Submit to server
  fetch("api/add-article.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // IMPORTANT: Set flag to prevent auto-save
        isPublished = true;
        
        // Clear any pending auto-save
        clearTimeout(autoSaveTimeout);
        
        // Clear draft from localStorage
        localStorage.removeItem("article_draft");
        localStorage.removeItem("article_draft_timestamp");
        
        showSuccess("Article published successfully!");
        
        // Reset form after successful submission
        setTimeout(() => {
          if (data.articleId) {
            window.location.href = `article.html?id=${data.articleId}`;
          } else {
            resetForm();
          }
        }, 2000);
      } else {
        showError(data.error || "Failed to publish article. Please try again.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showError("An error occurred while publishing the article.");
    })
    .finally(() => {
      // Restore button
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    });
}

// Modified auto-save function to check the published flag
function autoSave() {
  // Don't auto-save if article has been published
  if (isAutoSaving || isPublished) return;

  clearTimeout(autoSaveTimeout);
  autoSaveTimeout = setTimeout(() => {
    // Double-check the flag before saving
    if (isPublished) return;
    
    isAutoSaving = true;

    const formData = {
      title: document.getElementById("title").value,
      category: document.getElementById("category").value,
      author: document.getElementById("author").value,
      tags: document.getElementById("tags").value,
      excerpt: document.getElementById("excerpt").value,
      content: tinymce.get("content") ? tinymce.get("content").getContent() : "",
    };

    // Only save if there's meaningful content
    if (formData.title?.trim() || formData.content?.trim()) {
      localStorage.setItem("article_draft", JSON.stringify(formData));
      localStorage.setItem("article_draft_timestamp", Date.now().toString());

      // Show brief save indicator
      showAutoSaveIndicator();
    }

    isAutoSaving = false;
  }, 3000);
}

      function togglePreview() {
        const formMode = document.getElementById("form-mode");
        const previewMode = document.getElementById("preview-mode");

        if (isPreviewMode) {
          // Switch to edit mode
          formMode.style.display = "block";
          previewMode.style.display = "none";
          isPreviewMode = false;
        } else {
          // Validate before showing preview
          if (!validateForm()) {
            showError("Please fix the validation errors before previewing.");
            return;
          }
          // Switch to preview mode
          updatePreview();
          formMode.style.display = "none";
          previewMode.style.display = "block";
          isPreviewMode = true;
        }
      }

      function updatePreview() {
        const title =
          document.getElementById("title").value || "Untitled Article";
        const category =
          document.getElementById("category").value || "Uncategorized";
        const author =
          document.getElementById("author").value || "Unknown Author";
        const tags = document.getElementById("tags").value;
        const excerpt = document.getElementById("excerpt").value;

        let content = "";
        if (tinyMCEInitialized && tinymce.get("content")) {
          content = tinymce.get("content").getContent();
        }

        // Update preview elements
        document.getElementById("preview-title").textContent = title;
        document.getElementById("preview-category").textContent = category;
        document.getElementById("preview-author").textContent = author;

        // Set current date
        const today = new Date().toLocaleDateString("en-US", {
          year: "numeric",
          month: "long",
          day: "numeric",
        });
        document.getElementById("preview-date").textContent = today;

        // Estimate read time
        const wordCount = content.replace(/<[^>]*>/g, "").split(/\s+/).length;
        const readTime = Math.ceil(wordCount / 225);
        document.getElementById(
          "preview-read-time"
        ).textContent = `${readTime} min read`;

        // Set description
        const description = excerpt || createExcerpt(content, 200);
        document.getElementById("preview-description").textContent =
          description;

        // Set content
        document.getElementById("preview-content").innerHTML = content;

        // Set tags
        if (tags.trim()) {
          const tagElements = tags
            .split(",")
            .map((tag) => tag.trim())
            .filter((tag) => tag.length > 0)
            .map((tag) => `<span class="preview-tag">${tag}</span>`)
            .join("");
          document.getElementById("preview-tags").innerHTML = tagElements;
        } else {
          document.getElementById("preview-tags").innerHTML = "";
        }
      }

      function createExcerpt(text, characterLimit = 200) {
        const plainText = text.replace(/<[^>]*>/g, "");
        if (plainText.length <= characterLimit) return plainText;
        return plainText.substring(0, characterLimit).trim() + "...";
      }

      function resetForm() {
        document.getElementById("article-form").reset();
        if (tinyMCEInitialized && tinymce.get("content")) {
          tinymce.get("content").setContent("");
        }
        hideMessages();
        // Clear validation errors
        document.querySelectorAll(".validation-error").forEach((el) => {
          el.style.display = "none";
          el.textContent = "";
        });
      }

      function showSuccess(message) {
        hideMessages();
        const successDiv = document.getElementById("success-message");
        successDiv.textContent = message;
        successDiv.style.display = "block";
        successDiv.scrollIntoView({ behavior: "smooth" });
      }

      function showError(message) {
        hideMessages();
        const errorDiv = document.getElementById("error-message");
        errorDiv.textContent = message;
        errorDiv.style.display = "block";
        errorDiv.scrollIntoView({ behavior: "smooth" });
      }

      function hideMessages() {
        document.getElementById("success-message").style.display = "none";
        document.getElementById("error-message").style.display = "none";
      }

      // Sidebar functions
      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
      }

      // Close sidebar when clicking outside on mobile
      document.addEventListener("click", function (event) {
        const sidebar = document.getElementById("sidebar");
        const menuToggle = document.querySelector(".menu-toggle");

        if (
          window.innerWidth <= 768 &&
          !sidebar.contains(event.target) &&
          !menuToggle.contains(event.target) &&
          sidebar.classList.contains("active")
        ) {
          sidebar.classList.remove("active");
        }
      });

      // Auto-save draft with better timing and feedback
      let autoSaveTimeout;
      let isAutoSaving = false;

      function autoSave() {
        if (isAutoSaving) return;

        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
          isAutoSaving = true;

          const formData = {
            title: document.getElementById("title").value,
            category: document.getElementById("category").value,
            author: document.getElementById("author").value,
            tags: document.getElementById("tags").value,
            excerpt: document.getElementById("excerpt").value,
            content: tinymce.get("content")
              ? tinymce.get("content").getContent()
              : "",
          };

          // Only save if there's meaningful content
          if (formData.title?.trim() || formData.content?.trim()) {
            localStorage.setItem("article_draft", JSON.stringify(formData));
            localStorage.setItem(
              "article_draft_timestamp",
              Date.now().toString()
            );

            // Show brief save indicator
            showAutoSaveIndicator();
          }

          isAutoSaving = false;
        }, 3000); // Increased to 3 seconds for better performance
      }

      function showAutoSaveIndicator() {
        const indicator = document.getElementById("autosave-indicator");
        if (indicator) {
          indicator.style.display = "flex";
          indicator.classList.add("show");

          setTimeout(() => {
            indicator.classList.remove("show");
            setTimeout(() => {
              indicator.style.display = "none";
            }, 300);
          }, 2000);
        }
      }

      // Load draft on page load with improved modal
      window.addEventListener("load", function () {
        const draft = localStorage.getItem("article_draft");
        if (draft) {
          try {
            const draftInfo = getDraftInfo();
            if (draftInfo) {
              // Update modal content
              document.getElementById("draft-title").textContent =
                draftInfo.title;
              document.getElementById("draft-time").textContent =
                draftInfo.timeAgo;
              document.getElementById(
                "draft-words"
              ).textContent = `${draftInfo.wordCount} words`;

              // Show modal after a brief delay
              setTimeout(() => {
                showDraftModal();
              }, 500);
            }
          } catch (e) {
            console.error("Error checking draft:", e);
            localStorage.removeItem("article_draft");
            localStorage.removeItem("article_draft_timestamp");
          }
        }
      });

      // Add auto-save listeners
      ["title", "category", "author", "tags", "excerpt"].forEach((id) => {
        const element = document.getElementById(id);
        if (element) {
          element.addEventListener("input", autoSave);
        }
      });

      // Close modal when clicking outside
      document.addEventListener("click", function (event) {
        const modal = document.getElementById("draft-modal");
        const modalContent = document.querySelector(".draft-modal-content");

        if (
          modal &&
          modal.style.display === "flex" &&
          !modalContent.contains(event.target)
        ) {
          hideDraftModal();
        }
      });

      // Close modal with Escape key
      document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
          hideDraftModal();
        }
      });
    </script>
  </body>
</html>
