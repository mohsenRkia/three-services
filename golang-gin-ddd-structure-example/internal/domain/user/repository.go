package user

type Repository interface {
	FindByID(id string) (*User, error)
	Create(user *User) error
	Update(user *User) error
	Delete(id string) error
	List() ([]*User, error)
}
