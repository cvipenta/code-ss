**1. Queues (redis or kafka)**
 * Read about `event driven architecture`
 * AWS event driven arch

[https://aws.amazon.com/event-driven-architecture/](https://aws.amazon.com/event-driven-architecture/)
Amazon Simple Notification Service

**2. LOCK and MUTEX**
 * read about lock types (lock, mutex, semaphore)

[https://laravel.com/docs/9.x/cache#atomic-locks](https://laravel.com/docs/9.x/cache#atomic-locks)
[https://symfony.com/doc/current/components/lock.html](https://symfony.com/doc/current/components/lock.html)

[https://symfony.com/doc/current/components/lock.html](https://symfony.com/doc/current/components/lock.html)

**3. Data Dog or similar tools (Kibana or NewRelic)**
[https://www.datadoghq.com/product/error-tracking/](https://www.datadoghq.com/product/error-tracking/)

**4. OWASP Top 10 2021**  
Top 10: 
> **A01 Broken Access Control** (https://owasp.org/Top10/A01_2021-Broken_Access_Control/) 

> **A02 Cryptographic Failures** (Sensitive Data Exposure)

> **A03 Injection**  
    CWE-79: Cross-site Scripting  
    CWE-89: SQL Injection  
    CWE-73: External Control of File Name or Path

> **A04 Insecure Design**

> **A05 Security Misconfiguration**

> **A06 Vulnerable and Outdated Components**

> **A07 Identification and Authentication Failures** (Broken Authentication)  
    CWE-297: Improper Validation of Certificate with Host Mismatch  
    CWE-287: Improper Authentication  
    CWE-384: Session Fixation  

> **A08 Software and Data Integrity Failures**

> **A09 Security Logging and Monitoring Failures**  
    CWE-778 Insufficient Logging  
    CWE-117 Improper Output Neutralization for Logs  
    CWE-223 Omission of Security-relevant Information  
    CWE-532 Insertion of Sensitive Information into Log File

> **A10 Server Side Request Forgery (SSRF)**


\
Next:
   - Code Quality issues
   - Denial of Service
   - Memory Management Errors 

**OWASP scanning tools**:
* https://www.zaproxy.org/

\
**5. How `https` works**
[https://www.thesslstore.com/blog/how-does-https-work/](https://www.thesslstore.com/blog/how-does-https-work/)

**6. SOLID**
https://laracasts.com/series/solid-principles-in-php


**7. DESIGN PATTERNS**

**creational**: factory method, singleton, prototype, builder  
**structural**: decorator, adapter, facade, bridge  
**behavioral**: observer, strategy, template, Chain of responsibility, command  


https://laracasts.com/series/design-patterns-in-php

**8. Php library `Money`**
Documentation: https://www.moneyphp.org/en/stable/
    
`composer require moneyphp/money`

**9. REST verbs**
https://medium.com/@pawanpiumal1/common-http-verbs-in-restful-web-services-b517c90637bb

**10. Laravel service provider**
- AppServiceProvider
- read about differences between boot() and register()
  - The **register()** method is where you should add bindings to the service container. 
  - The **boot()** method is for performing actions after all service providers have registered their services.

Best answer: https://laracasts.com/discuss/channels/general-discussion/difference-between-boot-and-register-method?reply=4507

**11. Unit tests - dataProvider**

**12. Laravel Middleware in general**
https://laravel.com/docs/9.x/middleware
