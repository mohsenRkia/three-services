package dto

import "myGolangFramework/internal/infrastructure/pagination"

type PaginationData[T any] struct {
	Data       []*T       `json:"data"`
	Pagination Pagination `json:"pagination"`
}

type Pagination struct {
	Page       int   `json:"page"`
	Limit      int   `json:"limit"`
	Total      int64 `json:"total"`
	TotalPages int   `json:"total_pages"`
}

func NewPaginationData[T any](items []*T, p *pagination.Pagination) *PaginationData[T] {
	totalPages := int((p.Total + int64(p.Limit) - 1) / int64(p.Limit))
	return &PaginationData[T]{
		Data: items,
		Pagination: Pagination{
			Page:       p.Page,
			Limit:      p.Limit,
			Total:      p.Total,
			TotalPages: totalPages,
		},
	}
}
