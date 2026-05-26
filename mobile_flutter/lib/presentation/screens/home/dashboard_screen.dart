import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../providers/auth_provider.dart';
import '../../providers/incident_provider.dart';
import '../../providers/vulnerability_provider.dart';
import '../../widgets/dashboard/statistic_card.dart';
import '../../widgets/dashboard/security_score_card.dart';
import '../../widgets/dashboard/recent_activity_card.dart';

class DashboardScreen extends ConsumerStatefulWidget {
  const DashboardScreen({super.key});

  @override
  ConsumerState<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends ConsumerState<DashboardScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(incidentProvider.notifier).loadIncidents();
    ref.read(vulnerabilityProvider.notifier).loadVulnerabilities();
  }

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(authProvider).user;
    final incidentState = ref.watch(incidentProvider);
    final vulnState = ref.watch(vulnerabilityProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Dashboard'),
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
          await ref.read(vulnerabilityProvider.notifier).loadVulnerabilities();
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              const SecurityScoreCard(score: 78),
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
                    value: '${incidentState.incidents.where((i) => i.status == 'open').length}',
                    icon: Icons.warning,
                    color: Colors.red,
                  ),
                  StatisticCard(
                    title: 'Vulnerabilities',
                    value: '${vulnState.vulnerabilities.length}',
                    icon: Icons.bug_report,
                    color: Colors.orange,
                  ),
                  StatisticCard(
                    title: 'Compliance Rate',
                    value: '85%',
                    icon: Icons.check_circle,
                    color: Colors.green,
                  ),
                  StatisticCard(
                    title: 'Active Sessions',
                    value: '24',
                    icon: Icons.devices,
                    color: Colors.blue,
                  ),
                ],
              ),
              const SizedBox(height: 16),
              const RecentActivityCard(),
            ],
          ),
        ),
      ),
    );
  }
}