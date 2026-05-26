import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';

class ViewerDashboard extends ConsumerWidget {
  const ViewerDashboard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final user = ref.watch(authProvider).user;
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications),
            onPressed: () {},
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF7F8C8D), Color(0xFF6C7A89)],
                ),
                borderRadius: BorderRadius.circular(16),
              ),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 30,
                    backgroundColor: Colors.white,
                    child: Text(
                      user?.initials ?? 'VW',
                      style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFF7F8C8D)),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Welcome, ${user?.name ?? 'Viewer'}',
                          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          RoleConstants.roleDisplayNames[RoleConstants.viewer] ?? 'Viewer',
                          style: const TextStyle(color: Colors.white70),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'Security Overview',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            GridView.count(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              crossAxisCount: 2,
              mainAxisSpacing: 16,
              crossAxisSpacing: 16,
              childAspectRatio: 1.5,
              children: [
                StatisticCard(
                  title: 'Security Score',
                  value: '78%',
                  icon: Icons.security,
                  color: Colors.blue,
                ),
                StatisticCard(
                  title: 'Open Incidents',
                  value: '12',
                  icon: Icons.warning,
                  color: Colors.red,
                ),
                StatisticCard(
                  title: 'Vulnerabilities',
                  value: '47',
                  icon: Icons.bug_report,
                  color: Colors.orange,
                ),
                StatisticCard(
                  title: 'Compliance',
                  value: '85%',
                  icon: Icons.check_circle,
                  color: Colors.green,
                ),
              ],
            ),
            const SizedBox(height: 24),
            const Text(
              'Recent Reports',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            ..._getRecentReports().map((report) => ListTile(
              leading: const Icon(Icons.description, color: Colors.blue),
              title: Text(report['name']),
              subtitle: Text(report['date']),
              trailing: const Icon(Icons.chevron_right),
              onTap: () {},
            )),
          ],
        ),
      ),
    );
  }

  List<Map<String, dynamic>> _getRecentReports() {
    return [
      {'name': 'Q4 Security Summary', 'date': '2024-01-15'},
      {'name': 'Vulnerability Report', 'date': '2024-01-10'},
      {'name': 'Compliance Status', 'date': '2024-01-05'},
    ];
  }
}