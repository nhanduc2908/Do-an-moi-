import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';

class AuditorDashboard extends ConsumerWidget {
  const AuditorDashboard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final user = ref.watch(authProvider).user;
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Auditor Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.print),
            onPressed: () {},
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {},
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildHeader(user),
              const SizedBox(height: 24),
              const Text(
                'Audit Overview',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              _buildStatsGrid(),
              const SizedBox(height: 24),
              const Text(
                'Recent Audit Events',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              _buildRecentEvents(),
              const SizedBox(height: 24),
              const Text(
                'Quick Actions',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              _buildQuickActions(),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeader(user) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF95A5A6), Color(0xFF7F8C8D)],
        ),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Row(
        children: [
          CircleAvatar(
            radius: 30,
            backgroundColor: Colors.white,
            child: Text(
              user?.initials ?? 'AU',
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFF95A5A6)),
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Welcome, ${user?.name ?? 'Auditor'}',
                  style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                ),
                const SizedBox(height: 4),
                Text(
                  RoleConstants.roleDisplayNames[RoleConstants.auditor] ?? 'Auditor',
                  style: const TextStyle(color: Colors.white70),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatsGrid() {
    return GridView.count(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      crossAxisCount: 2,
      mainAxisSpacing: 16,
      crossAxisSpacing: 16,
      childAspectRatio: 1.5,
      children: [
        StatisticCard(
          title: 'Total Events',
          value: '15,234',
          icon: Icons.event,
          color: Colors.blue,
        ),
        StatisticCard(
          title: 'Active Users',
          value: '156',
          icon: Icons.people,
          color: Colors.green,
        ),
        StatisticCard(
          title: 'Audit Logs',
          value: '8,432',
          icon: Icons.history,
          color: Colors.orange,
        ),
        StatisticCard(
          title: 'Compliance Score',
          value: '85%',
          icon: Icons.check_circle,
          color: Colors.purple,
        ),
      ],
    );
  }

  Widget _buildRecentEvents() {
    final events = [
      {'time': '10:30', 'user': 'admin@demo.com', 'action': 'Login', 'ip': '192.168.1.1'},
      {'time': '09:45', 'user': 'security@demo.com', 'action': 'Role Changed', 'ip': '192.168.1.50'},
      {'time': '08:20', 'user': 'system', 'action': 'Backup Completed', 'ip': '-'},
      {'time': '07:55', 'user': 'user@demo.com', 'action': 'Password Change', 'ip': '192.168.1.100'},
    ];
    
    return Column(
      children: events.map((event) => Card(
        margin: const EdgeInsets.only(bottom: 8),
        child: ListTile(
          leading: const Icon(Icons.history, color: Colors.blue),
          title: Text(event['action'], style: const TextStyle(fontWeight: FontWeight.bold)),
          subtitle: Text('User: ${event['user']} • IP: ${event['ip']}'),
          trailing: Text(event['time'], style: const TextStyle(color: Colors.grey)),
          onTap: () {},
        ),
      )).toList(),
    );
  }

  Widget _buildQuickActions() {
    return Row(
      children: [
        Expanded(
          child: Card(
            child: InkWell(
              onTap: () {},
              borderRadius: BorderRadius.circular(12),
              child: const Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  children: [
                    Icon(Icons.assignment, size: 32, color: Colors.blue),
                    SizedBox(height: 8),
                    Text('View Audit Logs', textAlign: TextAlign.center),
                  ],
                ),
              ),
            ),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Card(
            child: InkWell(
              onTap: () {},
              borderRadius: BorderRadius.circular(12),
              child: const Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  children: [
                    Icon(Icons.report, size: 32, color: Colors.green),
                    SizedBox(height: 8),
                    Text('Generate Report', textAlign: TextAlign.center),
                  ],
                ),
              ),
            ),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Card(
            child: InkWell(
              onTap: () {},
              borderRadius: BorderRadius.circular(12),
              child: const Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  children: [
                    Icon(Icons.checklist, size: 32, color: Colors.orange),
                    SizedBox(height: 8),
                    Text('Compliance Check', textAlign: TextAlign.center),
                  ],
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }
}