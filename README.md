## Goal
Delete automated, notification-style messages from services like GitHub, Trac, Twitter, etc.

## Why
These messages are purely for notification and have very little inherent value beyond their initial reading. Deleting these messages cleans up your inbox, leaving behind the meat and potatoes: correspondences, specs, love notes, etc.

## How
purgemail runs through your email accounts and executes user-defined filters, deleting messages which match the filter. Filters are provided for common services.

See config.dist.php to see an example configuration.