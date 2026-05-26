import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../providers/incident_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';

class SecurityManagerDashboard extends ConsumerStatefulWidget {
  const SecurityManagerDashboard({super.key});

  @override
  ConsumerState<SecurityManagerDashboard> createState() => _SecurityManagerDashboardState();
}

class _SecurityManagerDashboardState extends ConsumerState<SecurityManagerDashboard> {
  @override
  void initState() {
    super.initState();
    ref.read(incidentProvider.notifier).loadIncidents();
    ref.read(incidentProvider.notifier).loadStats();
  }

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(authProvider).user;
    final incidentState = ref.watch(incidentProvider);
    
    final openIncidents = incidentState.incidents.where((i) => i.status == 'open').length;
    final criticalIncidents = incidentState.incidents.where((i) => i.severity == 'critical').length;
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Security Operations Center'),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications),
            onPressed: () {},
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await ref.read(incidentProvider.notifier).loadIncidents();
          await ref.read(incidentProvider.notifier).loadStats();
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFFE67E22), Color(0xFFD35400)],
                  ),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Row(
                  children: [
                    CircleAvatar(
                      radius: 30,
                      backgroundColor: Colors.white,
                      child: Text(
                        user?.initials ?? 'SM',
                        style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFFE67E22)),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Welcome, ${user?.name ?? 'Security Manager'}',
                            style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            RoleConstants.roleDisplayNames[RoleConstants.securityManager] ?? 'Security Manager',
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
                    title: 'Open Incidents',
                    value: '$openIncidents',
                    icon: Icons.warning,
                    color: Colors.red,
                  ),
                  StatisticCard(
                    title: 'Critical',
                    value: '$criticalIncidents',
                    icon: Icons.error,
                    color: Colors.orange,
                  ),
                  StatisticCard(
                    title: 'Team Members',
                    value: '8',
                    icon: Icons.group,
                    color: Colors.blue,
                  ),
                  StatisticCard(
                    title: 'Security Score',
                    value: '78%',
                    icon: Icons.security,
                    color: Colors.green,
                  ),
                ],
              ),
              const SizedBox(height: 24),
              const Text(
                'Recent Incidents',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              ...incidentState.incidents.take(5).map((incident) => Card(
                margin: const EdgeInsets.only(bottom: 12),
                child: ListTile(
                  leading: CircleAvatar(
                    backgroundColor: _getSeverityColor(incident.severity ?? 'medium'),
                    child: Icon(_getSeverityIcon(incident.severity ?? 'medium'), color: Colors.white),
                  ),
                  title: Text(incident.title ?? 'Unknown', style: const TextStyle(fontWeight: FontWeight.bold)),
                  subtitle: Text('${incident.incidentCode} • ${incident.status}'),
                  trailing: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: _getStatusColor(incident.status ?? 'open').withOpacity(0.2),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      incident.status ?? 'open',
                      style: TextStyle(color: _getStatusColor(incident.status ?? 'open')),
                    ),
                  ),
                  onTap: () {},
                ),
              )),
            ],
          ),
        ),
      ),
    );
  }

  Color _getSeverityColor(String severity) {
    switch (severity.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow;
      default: return Colors.green;
    }
  }

  IconData _getSeverityIcon(String severity) {
    switch (severity.toLowerCase()) {
      case 'critical': return Icons.error;
      case 'high': return Icons.warning;
      default: return Icons.info;
    }
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'open': return Colors.red;
      case 'investigating': return Colors.orange;
      case 'resolved': return Colors.green;
      default: return Colors.grey;
    }
  }
}