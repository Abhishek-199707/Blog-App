<template>
    <div class="pagination">
      <!-- Conditional Previous Button -->
      <button v-if="currentPage > 1" @click="prevPage">Previous</button>

      <!-- Page Numbers -->
      <div class="page-numbers">
        <!-- Always Show Page 1 -->
        <button v-if="showFirstPage" @click="goToPage(1)" class="page-number">
          1
        </button>
        <span v-if="showFirstEllipsis" class="ellipsis">...</span>

        <button
          v-for="page in visiblePages"
          :key="page"
          @click="goToPage(page)"
          :class="{ active: page === currentPage }"
          class="page-number"
        >
          {{ page }}
        </button>

        <span v-if="showLastEllipsis" class="ellipsis">...</span>
        <button
          v-if="showLastPage"
          @click="goToPage(totalPages)"
          class="page-number"
        >
          {{ totalPages }}
        </button>
      </div>

      <!-- Conditional Next Button -->
      <button v-if="currentPage < totalPages" @click="nextPage">Next</button>
    </div>
  </template>

  <script>
  export default {
    props: {
      currentPage: {
        type: Number,
        required: true
      },
      totalPages: {
        type: Number,
        required: true
      }
    },
    computed: {
      visiblePages() {
        const pages = [];
        const pageLimit = 3; // Number of pages to show before and after the current page
        let start = Math.max(2, this.currentPage - pageLimit);
        let end = Math.min(this.totalPages - 1, this.currentPage + pageLimit);

        if (this.totalPages <= 5) {
          start = 2;
          end = this.totalPages - 1;
        }

        for (let i = start; i <= end; i++) {
          pages.push(i);
        }

        return pages;
      },
      showFirstPage() {
        return this.totalPages > 1;
      },
      showFirstEllipsis() {
        return this.visiblePages[0] > 2;
      },
      showLastEllipsis() {
        return this.visiblePages[this.visiblePages.length - 1] < this.totalPages - 1;
      },
      showLastPage() {
        return this.totalPages > 1 && !this.showLastEllipsis;
      }
    },
    methods: {
      prevPage() {
        if (this.currentPage > 1) {
          this.$emit('page-changed', this.currentPage - 1);
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.$emit('page-changed', this.currentPage + 1);
        }
      },
      goToPage(page) {
        if (page !== this.currentPage) {
          this.$emit('page-changed', page);
        }
      }
    }
  };
  </script>


  <style scoped>
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
  }

  button {
    padding: 0.5rem 1rem;
    border: none;
    background-color: #1a202c;
    color: white;
    cursor: pointer;
    border-radius: 4px;
  }

  button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }

  .page-numbers {
    display: flex;
    gap: 0.5rem;
  }

  .page-number {
    padding: 0.5rem 1rem;
    border: 1px solid #1a202c;
    border-radius: 4px;
    background-color: white;
    color: #1a202c;
    cursor: pointer;
  }

  .page-number.active {
    background-color: #007bff;
    color: white;
  }

  .ellipsis {
    padding: 0.5rem;
    color: #1a202c;
  }
  </style>


