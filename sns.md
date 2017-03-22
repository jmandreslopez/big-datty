FORMAT: 1A

# Amazon SNS Format

## Account

+ Verify Account

		{
			"action": "verification",
			"account": 1,
			"status": "passed"|"failed"
		}
		
## Order

+ New Order

		{
			"action": "new",
			"order": 1
		}
		
## Product

+ New Product

		{
			"action": "new",
			"product": 1
		}
		
## Product Review

+ New Product Review

		{
			"action": "new",
			"review": 1
		}
		
## Product Question

+ New Product Question

		{
			"action": "new",
			"order": 1
		}
		
## Product Hijack

+ New Product Hijack

		{
			"action": "new",
			"order": 1
		}
		
## Feedback

+ New Feedback

		{
			"action": "new",
			"feedback": 1
		}