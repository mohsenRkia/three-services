package user

import (
	"myGolangFramework/internal/application"
	sharedDto "myGolangFramework/internal/application/shared/dto"
	"myGolangFramework/internal/application/user/dto/response"
	"myGolangFramework/internal/bootstrap/config/db"
	"myGolangFramework/internal/domain"
	"myGolangFramework/internal/domain/user"
	"myGolangFramework/internal/infrastructure/email"
	"myGolangFramework/internal/infrastructure/logging"
	"myGolangFramework/internal/infrastructure/pagination"
	userRepo "myGolangFramework/internal/infrastructure/persistence/user"
	"myGolangFramework/internal/infrastructure/sms"
)

type Service struct {
	uow     application.UnitOfWork
	repo    domain.UserRepository
	Emailer email.EmailSender
	SMS     sms.SMSSender
	Logger  logging.Logger
}

func NewService() *Service {
	return &Service{
		uow:  db.UoWInstance(),
		repo: userRepo.NewUserRepository(db.Connection()),
	}
}

func (s *Service) List(page, limit int) (*sharedDto.PaginationData[response.UserResponseDTO], error) {
	p := pagination.New(page, limit)
	users, err := s.repo.List(p)
	if err != nil {
		return response.ToResponsesUserDTO(nil, p), err
	}
	return response.ToResponsesUserDTO(users, p), nil
}

func (s *Service) GetUser(id string) (*response.UserResponseDTO, error) {
	data, err := s.repo.FindByID(id)
	if err != nil {
		return nil, err
	}
	return response.ToResponseUserDTO(data), nil
}

func (s *Service) CreateUser(email, password, phone string) (*response.UserResponseDTO, error) {
	//Async operations first create user and next do other works

	u, err := user.NewUser(email, password, phone)
	if err != nil {
		return nil, err
	}

	var us *user.User
	//UnitOfWork For Transaction
	storeErr := s.uow.Do(func(r domain.TXRepositoryProviderInterface) error {
		us, err = r.User().Store(u)
		if err != nil {
			return err
		}
		///other creation for transactions

		return nil
	})

	if storeErr != nil {
		return nil, storeErr
	}

	//Concurrency
	go s.Emailer.SendWelcomeEmail(us)
	go s.SMS.SendSMS(us.Phone, "Sms Sent...")
	go s.Logger.AddLog("User created successfully", us.ID)
	return response.ToResponseUserDTO(us), nil
}
