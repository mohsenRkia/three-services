```mermaid
graph TD
  A[Client Browser / Mobile App] -->|HTTP/HTTPS| B[Nginx Load Balancer]
  B --> C1[Laravel App Instance #1]
  B --> C2[Laravel App Instance #2]
  B --> C3[Laravel App Instance #3]

  C1 -->|Cache/Session| D[Redis]
  C2 -->|Cache/Session| D
  C3 -->|Cache/Session| D

  C1 -->|Publish Event| E[RabbitMQ]
  C2 -->|Publish Event| E
  C3 -->|Publish Event| E

  C1 -->|Read/Write Data| F[(MySQL/PostgreSQL)]
  C2 -->|Read/Write Data| F
  C3 -->|Read/Write Data| F

  E -->|Process Jobs| C1
  E -->|Process Jobs| C2
  E -->|Process Jobs| C3
