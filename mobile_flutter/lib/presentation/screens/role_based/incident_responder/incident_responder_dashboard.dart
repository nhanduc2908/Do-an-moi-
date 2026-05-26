import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../providers/incident_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';

class IncidentResponderDashboard extends ConsumerStatefulWidget {
  const IncidentResponderDashboard({super.key});

  @override
  ConsumerState<IncidentResponderDashboard> createState() => _IncidentResponderDashboardState();
}

class _IncidentResponderDashboardState extends ConsumerState<IncidentResponderDashboard> {
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
    
    final activeIncidents = incidentState.incidents.where((i) => i.status == 'open' || i.status == 'investigating').length;
    final criticalIncidents = incidentState.incidents.where((i) => i.severity == 'critical').length;
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Incident Response Center'),
        backgroundColor: Colors.red.shade700,
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications),
            onPressed: () {},
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => ref.read(incidentProvider.notifier).loadIncidents(),
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFFE84393), Color(0xFFC2185B)],
                  ),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Row(
                  children: [
                    CircleAvatar(
                      radius: 30,
                      backgroundColor: Colors.white,
                      child: Text(
                        user?.initials ?? 'IR',
                        style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFFE84393)),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Welcome, ${user?.name ?? 'Incident Responder'}',
                            style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            RoleConstants.roleDisplayNames[RoleConstants.incidentResponder] ?? 'Incident Responder',
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
                'Response Overview',
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
                    title: 'Active Incidents',
                    value: '$activeIncidents',
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
                    title: 'MT Response',
                    value: '12 min',
                    icon: Icons.timer,
                    color: Colors.blue,
                  ),
                  StatisticCard(
                    title: 'MT Recovery',
                    value: '45 min',
                    icon: Icons.recovery,
                    color: Colors.green,
                  ),
                ],
              ),
              const SizedBox(height: 24),
              const Text(
                'Active Incidents',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              ...incidentState.incidents.where((i) => i.status == 'open' || i.status == 'investigating').take(3).map((incident) => Card(
                margin: const EdgeInsets.only(bottom: 12),
                child: ListTile(
                  leading: CircleAvatar(
                    backgroundColor: incident.severity == 'critical' ? Colors.red : Colors.orange,
                    child: Text(incident.incidentCode?.substring(0, 3) ?? 'INC', style: const TextStyle(fontSize: 12, color: Colors.white)),
                  ),
                  title: Text(incident.title ?? 'Unknown', style: const TextStyle(fontWeight: Weight.bold)),
                  subtitle: Text('Severity: ${incident.severity} • Status: ${incident.status}'),
                  trailing: ElevatedButton(
                    onPressed: () {},
                    style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                    child: const Text('Respond', style: TextStyle(color: Colors.white)),
                  ),
                ),
              )),
              const SizedBox(height: 24),
              const Text(
                'Quick Actions',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              Row(
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
                              Icon(Icons.menu_book, size: 32, color: Colors.blue),
                              SizedBox(height: 8),
                              Text('Runbooks', textAlign: TextAlign.center),
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
                              Icon(Icons.analytics, size: 32, color: Colors.green),
                              SizedBox(height: 8),
                              Text('Forensics', textAlign: TextAlign.center),
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
                              Icon(Icons.block, size: 32, color: Colors.orange),
                              SizedBox(height: 8),
                              Text('Containment', textAlign: TextAlign.center),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}