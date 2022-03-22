##1. Queues (redis or kafka)
 * Read about `event driven architecture`
 * AWS event driven arch

[https://aws.amazon.com/event-driven-architecture/](https://aws.amazon.com/event-driven-architecture/)
Amazon Simple Notification Service

##2. LOCK and MUTEX
 * read about lock types (lock, mutex, semaphore)

[https://laravel.com/docs/9.x/cache#atomic-locks](https://laravel.com/docs/9.x/cache#atomic-locks)
[https://symfony.com/doc/current/components/lock.html](https://symfony.com/doc/current/components/lock.html)

[https://symfony.com/doc/current/components/lock.html](https://symfony.com/doc/current/components/lock.html)

##3. Data Dog or similar tools (Kibana or NewRelic)
[https://www.datadoghq.com/product/error-tracking/](https://www.datadoghq.com/product/error-tracking/)

##4. <font color="red">OWASP top 10</font>
Top 10:
   - A01 Broken Access Control (https://owasp.org/Top10/A01_2021-Broken_Access_Control/)
   - A02 Cryptographic Failures
   - A03 Injection
   - A04 Insecure Design
   - A05 Security Misconfiguration
   - A06 Vulnerable and Outdated Components
   - A07 Identification and Authentication Failures
   - A08 Software and Data Integrity Failures
   - A09 Security Logging and Monitoring Failures
   - A10 Server Side Request Forgery (SSRF)

Next:
   - Code Quality issues
   - Denial of Service
   - Memory Management Errors 

**Tools**:
* https://www.zaproxy.org/


##5. How `https` works
[https://www.thesslstore.com/blog/how-does-https-work/](https://www.thesslstore.com/blog/how-does-https-work/)

#6. SOLID
https://laracasts.com/series/solid-principles-in-php


#7. DESIGN PATTERNS
factory, decorator, facade, singleton, observer
https://laracasts.com/series/design-patterns-in-php

##8. Php library `Money`
Documentation: https://www.moneyphp.org/en/stable/
    
`composer require moneyphp/money`

##9. REST verbs
https://medium.com/@pawanpiumal1/common-http-verbs-in-restful-web-services-b517c90637bb

##10. Laravel service provider
- AppServiceProvider
- read about differences between boot() and register()
  - The **register()** method is where you should add bindings to the service container. 
  - The **boot()** method is for performing actions after all service providers have registered their services.

Best answer: https://laracasts.com/discuss/channels/general-discussion/difference-between-boot-and-register-method?reply=4507

##11. Unit tests - dataProvider
Se fac teste unitare la tot codul, la git push se ruleaza suita de teste

##12. Laravel Middleware in general
https://laravel.com/docs/9.x/middleware
