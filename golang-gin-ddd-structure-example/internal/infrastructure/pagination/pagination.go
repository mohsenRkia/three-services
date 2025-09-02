package pagination

import "gorm.io/gorm"

type Pagination struct {
	Page  int
	Limit int
	Total int64
}

func New(page, limit int) *Pagination {
	if page <= 0 {
		page = 1
	}
	if limit > 100 {
		limit = 100
	}
	if limit <= 0 {
		limit = 10
	}
	return &Pagination{Page: page, Limit: limit}
}

func (p *Pagination) Scope() func(db *gorm.DB) *gorm.DB {
	return func(db *gorm.DB) *gorm.DB {
		offset := (p.Page - 1) * p.Limit
		return db.Offset(offset).Limit(p.Limit)
	}
}

func (p *Pagination) WithTotal(db *gorm.DB, model interface{}) error {
	return db.Model(model).Count(&p.Total).Error
}
