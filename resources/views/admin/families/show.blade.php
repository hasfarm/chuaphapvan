@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-people-roof" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Chi Tiết Gia Đình
        </h1>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.families.edit', $family) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Chỉnh Sửa
            </a>
            <a href="{{ route('admin.families.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
            <div><strong>Tên gia đình:</strong><br>{{ $family->family_name }}</div>
            <div><strong>Mã gia đình:</strong><br>{{ $family->family_code ?? '—' }}</div>
            <div><strong>Chủ hộ:</strong><br>{{ $family->head_name ?? '—' }}</div>
            <div><strong>Điện thoại:</strong><br>{{ $family->phone ?? '—' }}</div>
            <div><strong>Email:</strong><br>{{ $family->email ?? '—' }}</div>
            <div><strong>Trạng thái:</strong><br>{{ $family->status === 'active' ? 'Hoạt động' : 'Ngưng hoạt động' }}</div>
            <div style="grid-column: 1 / -1;"><strong>Địa chỉ:</strong><br>{{ $family->address ?? '—' }}</div>
            <div style="grid-column: 1 / -1;"><strong>Ghi chú:</strong><br>{{ $family->notes ?? '—' }}</div>
        </div>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); margin-top: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.5rem;">
            <h3 style="margin: 0; color: var(--dark); display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-diagram-project" style="color: var(--primary-green);"></i>
                Cây Gia Đình
            </h3>

            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span id="chart-save-status" style="font-size: 0.85rem; color: var(--gray);"></span>
                <button type="button" id="reset-chart-layout-btn" class="btn btn-secondary" style="padding: 0.45rem 0.85rem;">
                    <i class="fas fa-rotate-left"></i>
                    Reset bố cục
                </button>
                <button type="button" id="save-chart-layout-btn" class="btn btn-primary" style="padding: 0.45rem 0.85rem;">
                    <i class="fas fa-save"></i>
                    Lưu vị trí
                </button>
            </div>
        </div>

        @if ($family->contacts->isEmpty())
            <div style="padding: 1rem; border: 1px dashed var(--light-gray); border-radius: 0.75rem; color: var(--gray);">
                Chưa có thành viên trong gia đình để vẽ cây.
            </div>
        @else
            <div style="overflow-x: auto; background: linear-gradient(180deg, #fcfcfc 0%, #f8fafc 100%); border: 1px solid var(--light-gray); border-radius: 0.75rem; padding: 1rem;">
                <div id="family-tree-chart" style="width: 100%; min-width: 920px; height: 760px;"></div>
                </div>

            <div style="margin-top: 1rem; color: var(--gray); font-size: 0.92rem;">
                <span style="display: inline-flex; align-items: center; gap: 0.35rem; margin-right: 1rem;">
                    <span style="display:inline-block; width: 36px; height: 0; border-top: 2px solid #22a447;"></span>
                    Cha/mẹ → Con
                </span>
                <span style="display: inline-flex; align-items: center; gap: 0.35rem; margin-right: 1rem;">
                    <span style="color: #dc2626; font-size: 1rem; line-height: 1;">❤</span>
                    Hôn phối
                </span>
                <span>Có thể kéo và lăn chuột để zoom.</span>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>
    <script>
        (function() {
            const chartContainer = document.getElementById('family-tree-chart');
            if (!chartContainer || typeof d3 === 'undefined') {
                return;
            }

            const chartData = @json($familyTreeChart);
            const initialSavedLayout = @json($savedChartLayout['nodes'] ?? []);
            const saveLayoutUrl = @json(route('admin.families.chart-layout.save', $family));
            const resetLayoutUrl = @json(route('admin.families.chart-layout.reset', $family));
            const csrfToken = @json(csrf_token());

            const saveButton = document.getElementById('save-chart-layout-btn');
            const resetButton = document.getElementById('reset-chart-layout-btn');
            const saveStatus = document.getElementById('chart-save-status');
            if (!chartData || !Array.isArray(chartData.nodes) || !Array.isArray(chartData.edges)) {
                return;
            }

            const savedPositionMap = new Map(
                (Array.isArray(initialSavedLayout) ? initialSavedLayout : [])
                    .filter(node => Number.isFinite(Number(node?.id)) && Number.isFinite(Number(node?.x)) && Number.isFinite(Number(node?.y)))
                    .map(node => [Number(node.id), { x: Number(node.x), y: Number(node.y) }])
            );

            const rootStyles = getComputedStyle(document.documentElement);
            const darkColor = (rootStyles.getPropertyValue('--dark') || '#1f2937').trim();

            const allNodes = chartData.nodes.map(node => {
                const genderIcon = node.gender === 'male' ? '♂' : (node.gender === 'female' ? '♀' : '•');
                const genderColor = node.gender === 'male' ? '#0ea5e9' : (node.gender === 'female' ? '#ec4899' : '#6b7280');
                const badge = node.is_household_head ? '⭐ Chủ hộ' : (node.is_primary_contact ? '📞 Liên lạc chính' : '');
                const baseLabel = String(node.label || '');
                const baseLines = baseLabel
                    .split(/\r?\n|\\n/g)
                    .map(line => line.trim())
                    .filter(Boolean);

                if (baseLines.length === 0) {
                    baseLines.push('');
                }
                const lines = [...baseLines];
                if (node.life_detail) {
                    lines.push(String(node.life_detail));
                }

                if (badge) {
                    lines.push(badge);
                }

                return {
                    id: node.id,
                    genderIcon,
                    genderColor,
                    lines,
                    lifeStatus: node.life_status || 'alive',
                    ageSort: Number.isFinite(Number(node.age_sort)) ? Number(node.age_sort) : 99999999,
                    level: Number.isFinite(Number(node.level)) ? Number(node.level) : 0,
                };
            });

            const nodeMap = new Map(allNodes.map(node => [node.id, { ...node, children: [] }]));
            const spouseEdges = chartData.edges.filter(edge => edge.type === 'spouse');
            const parentChildEdges = chartData.edges.filter(edge => edge.type === 'parent_child');

            const parentByChild = new Map();
            parentChildEdges.forEach(edge => {
                if (!nodeMap.has(edge.from) || !nodeMap.has(edge.to) || parentByChild.has(edge.to)) {
                    return;
                }
                parentByChild.set(edge.to, edge.from);
            });

            parentByChild.forEach((parentId, childId) => {
                const parent = nodeMap.get(parentId);
                const child = nodeMap.get(childId);
                if (parent && child) {
                    parent.children.push(child);
                }
            });

            const rootNodes = allNodes
                .filter(node => !parentByChild.has(node.id))
                .map(node => nodeMap.get(node.id))
                .filter(Boolean);

            const treeSource = rootNodes.length === 1
                ? rootNodes[0]
                : { id: '__virtual_root__', lines: [''], children: rootNodes };

            const hierarchyRoot = d3.hierarchy(treeSource, d => d.children);
            const treeLayout = d3.tree().nodeSize([200, 150]);
            treeLayout(hierarchyRoot);

            const descendants = hierarchyRoot.descendants();
            const renderedNodes = descendants.filter(d => d.data.id !== '__virtual_root__');
            const minX = d3.min(renderedNodes, d => d.x) ?? 0;
            const maxX = d3.max(renderedNodes, d => d.x) ?? 0;
            const maxLevel = d3.max(allNodes, node => node.level) ?? 0;

            const margin = { top: 40, right: 80, bottom: 80, left: 80 };
            const width = Math.max(920, (maxX - minX) + margin.left + margin.right + 180);
            const levelGap = 170;
            const height = Math.max(760, maxLevel * levelGap + margin.top + margin.bottom + 180);

            chartContainer.innerHTML = '';
            const svg = d3.select(chartContainer)
                .append('svg')
                .attr('width', width)
                .attr('height', height)
                .style('display', 'block');

            const zoomLayer = svg.append('g');

            svg.call(
                d3.zoom()
                    .scaleExtent([0.4, 2.2])
                    .on('zoom', (event) => {
                        zoomLayer.attr('transform', event.transform);
                    })
            );

            const originX = margin.left - minX + 40;
            const originY = margin.top;
            const positionedNodes = new Map();
            const measureNodeSize = (lines) => {
                const longest = (lines || []).reduce((max, line) => Math.max(max, String(line || '').length), 0);
                const width = Math.max(156, (longest * 7.1) + 34);
                const height = Math.max(48, 28 + (Math.max((lines || []).length, 1) * 16));

                return { width, height };
            };

            renderedNodes.forEach(node => {
                const resolvedLevel = Number.isFinite(Number(node.data.level)) ? Number(node.data.level) : 0;
                const size = measureNodeSize(node.data.lines || []);
                positionedNodes.set(node.data.id, {
                    x: node.x + originX,
                    y: resolvedLevel * levelGap + originY,
                    lines: node.data.lines || [],
                    level: resolvedLevel,
                    ageSort: Number.isFinite(Number(node.data.ageSort)) ? Number(node.data.ageSort) : 99999999,
                    width: size.width,
                    height: size.height,
                });
            });

            const levelBuckets = new Map();
            positionedNodes.forEach((node, id) => {
                if (!levelBuckets.has(node.level)) {
                    levelBuckets.set(node.level, []);
                }
                levelBuckets.get(node.level).push({ id, ...node });
            });

            const pairKey = (a, b) => (a < b ? `${a}-${b}` : `${b}-${a}`);

            const candidateSpousePairs = [];
            const seenPairKeys = new Set();
            spouseEdges.forEach(edge => {
                const a = positionedNodes.get(edge.from);
                const b = positionedNodes.get(edge.to);
                if (!a || !b || a.level !== b.level) {
                    return;
                }

                const key = pairKey(edge.from, edge.to);
                if (seenPairKeys.has(key)) {
                    return;
                }
                seenPairKeys.add(key);

                candidateSpousePairs.push({
                    a: edge.from,
                    b: edge.to,
                    distance: Math.abs((a.x ?? 0) - (b.x ?? 0)),
                });
            });

            candidateSpousePairs.sort((left, right) => left.distance - right.distance);

            const spousePartner = new Map();
            const spousePairs = [];
            candidateSpousePairs.forEach(pair => {
                if (spousePartner.has(pair.a) || spousePartner.has(pair.b)) {
                    return;
                }
                spousePartner.set(pair.a, pair.b);
                spousePartner.set(pair.b, pair.a);
                spousePairs.push([pair.a, pair.b]);
            });

            levelBuckets.forEach(bucket => {
                bucket.sort((a, b) => a.x - b.x);
                const byId = new Map(bucket.map(node => [node.id, node]));
                const used = new Set();
                const blocks = [];

                bucket.forEach(node => {
                    if (used.has(node.id)) {
                        return;
                    }

                    const spouseId = spousePartner.get(node.id);
                    const spouse = spouseId ? byId.get(spouseId) : null;

                    if (spouse && !used.has(spouse.id)) {
                        const members = [node, spouse].sort((a, b) => a.ageSort - b.ageSort);
                        used.add(node.id);
                        used.add(spouse.id);
                        blocks.push({
                            type: 'couple',
                            members,
                            desiredCenter: (node.x + spouse.x) / 2,
                        });
                        return;
                    }

                    used.add(node.id);
                    blocks.push({
                        type: 'single',
                        members: [node],
                        desiredCenter: node.x,
                    });
                });

                const spouseInnerGap = 22;
                const blockGap = 28;

                blocks.forEach(block => {
                    if (block.type === 'couple') {
                        const left = block.members[0];
                        const right = block.members[1];
                        const totalWidth = (left.width / 2) + spouseInnerGap + (right.width / 2);
                        block.halfWidth = totalWidth / 2;
                    } else {
                        block.halfWidth = block.members[0].width / 2;
                    }
                });

                blocks.sort((a, b) => a.desiredCenter - b.desiredCenter);

                let previousRight = null;
                blocks.forEach((block, index) => {
                    let center = block.desiredCenter;
                    if (index > 0 && previousRight !== null) {
                        const minCenter = previousRight + blockGap + block.halfWidth;
                        if (center < minCenter) {
                            center = minCenter;
                        }
                    }

                    block.center = center;
                    previousRight = center + block.halfWidth;
                });

                const visualCenter = d3.mean(blocks, block => block.center) ?? 0;
                blocks.forEach(block => {
                    block.center -= visualCenter * 0.03;
                });

                const ageStep = 4;
                const singlesByAge = blocks
                    .filter(block => block.type === 'single')
                    .map(block => block.members[0])
                    .sort((a, b) => a.ageSort - b.ageSort);
                const ageCenterIndex = (singlesByAge.length - 1) / 2;
                const singleYOffsetById = new Map();
                singlesByAge.forEach((node, index) => {
                    singleYOffsetById.set(node.id, (index - ageCenterIndex) * ageStep);
                });

                blocks.forEach(block => {
                    if (block.type === 'couple') {
                        const left = block.members[0];
                        const right = block.members[1];
                        const centerDistance = ((left.width / 2) + spouseInnerGap + (right.width / 2));
                        const leftX = block.center - (centerDistance / 2);
                        const rightX = block.center + (centerDistance / 2);

                        left.x = leftX;
                        right.x = rightX;

                        const coupleY = Math.min(left.y, right.y);
                        left.y = coupleY;
                        right.y = coupleY;

                        positionedNodes.set(left.id, left);
                        positionedNodes.set(right.id, right);
                        return;
                    }

                    const single = block.members[0];
                    single.x = block.center;
                    single.y += singleYOffsetById.get(single.id) ?? 0;
                    positionedNodes.set(single.id, single);
                });

                bucket.forEach(node => {
                    positionedNodes.set(node.id, node);
                });
            });

            if (savedPositionMap.size > 0) {
                positionedNodes.forEach((node, id) => {
                    const saved = savedPositionMap.get(Number(id));
                    if (!saved) {
                        return;
                    }

                    node.x = saved.x;
                    node.y = saved.y;
                    positionedNodes.set(id, node);
                });
            }

            const drawParentPath = (startX, startY, endX, endY) => {
                const midY = startY + ((endY - startY) * 0.5);
                return `M ${startX} ${startY} L ${startX} ${midY} L ${endX} ${midY} L ${endX} ${endY}`;
            };

            const parentMapByChild = new Map();
            parentChildEdges.forEach(edge => {
                if (!positionedNodes.has(edge.from) || !positionedNodes.has(edge.to)) {
                    return;
                }

                if (!parentMapByChild.has(edge.to)) {
                    parentMapByChild.set(edge.to, new Set());
                }
                parentMapByChild.get(edge.to).add(edge.from);
            });

            const parentLinksLayer = zoomLayer.append('g');
            const spouseLinksLayer = zoomLayer.append('g');

            const renderConnections = () => {
                parentLinksLayer.selectAll('*').remove();
                spouseLinksLayer.selectAll('*').remove();

                const coupleCenterByPair = new Map();
                spousePairs.forEach(([leftId, rightId]) => {
                    const leftNode = positionedNodes.get(leftId);
                    const rightNode = positionedNodes.get(rightId);
                    if (!leftNode || !rightNode) {
                        return;
                    }

                    const key = pairKey(leftId, rightId);
                    coupleCenterByPair.set(key, {
                        x: (leftNode.x + rightNode.x) / 2,
                        y: Math.min(leftNode.y, rightNode.y),
                    });

                    spouseLinksLayer.append('line')
                        .attr('x1', leftNode.x)
                        .attr('y1', leftNode.y)
                        .attr('x2', rightNode.x)
                        .attr('y2', rightNode.y)
                        .attr('stroke', '#f59e9e')
                        .attr('stroke-width', 2)
                        .attr('stroke-dasharray', '6,4');

                    spouseLinksLayer.append('text')
                        .attr('x', (leftNode.x + rightNode.x) / 2)
                        .attr('y', (leftNode.y + rightNode.y) / 2 - 6)
                        .attr('text-anchor', 'middle')
                        .attr('fill', '#dc2626')
                        .style('font-size', '14px')
                        .text('❤');
                });

                parentMapByChild.forEach((parentSet, childId) => {
                    const child = positionedNodes.get(childId);
                    if (!child) {
                        return;
                    }

                    const parents = Array.from(parentSet)
                        .map(parentId => positionedNodes.get(parentId))
                        .filter(Boolean);

                    if (!parents.length) {
                        return;
                    }

                    let startX = parents[0].x;
                    let startY = parents[0].y + (parents[0].height / 2);

                    const spousePair = parents.length >= 2
                        ? parents
                            .map(parent => {
                                const partnerId = spousePartner.get(parent.id);
                                if (!partnerId || !parentSet.has(partnerId)) {
                                    return null;
                                }
                                const key = pairKey(parent.id, partnerId);
                                return {
                                    key,
                                    a: positionedNodes.get(parent.id),
                                    b: positionedNodes.get(partnerId),
                                };
                            })
                            .find(Boolean)
                        : null;

                    if (parents.length >= 2) {
                        if (spousePair && spousePair.a && spousePair.b) {
                            const pairCenter = coupleCenterByPair.get(spousePair.key);
                            startX = pairCenter ? pairCenter.x : ((spousePair.a.x + spousePair.b.x) / 2);
                            startY = pairCenter ? pairCenter.y : ((spousePair.a.y + spousePair.b.y) / 2);
                        } else {
                            const sortedParents = [...parents].sort((left, right) => left.x - right.x);
                            const leftParent = sortedParents[0];
                            const rightParent = sortedParents[1] ?? sortedParents[0];
                            startX = (leftParent.x + rightParent.x) / 2;
                            startY = (leftParent.y + rightParent.y) / 2;
                        }
                    }

                    const childTopY = child.y - (child.height / 2);
                    parentLinksLayer.append('path')
                        .attr('d', drawParentPath(startX, startY, child.x, childTopY))
                        .attr('fill', 'none')
                        .attr('stroke', '#9ca3af')
                        .attr('stroke-width', 2);
                });
            };

            renderConnections();

            const nodeGroup = zoomLayer.append('g')
                .selectAll('g')
                .data(renderedNodes)
                .join('g')
                .attr('transform', d => {
                    const positioned = positionedNodes.get(d.data.id);
                    const x = positioned ? positioned.x : (d.x + originX);
                    const y = positioned ? positioned.y : (d.y + originY);
                    return `translate(${x},${y})`;
                });

            nodeGroup.append('rect')
                .attr('x', d => -((positionedNodes.get(d.data.id)?.width ?? 156) / 2))
                .attr('y', d => -((positionedNodes.get(d.data.id)?.height ?? 48) / 2))
                .attr('width', d => positionedNodes.get(d.data.id)?.width ?? 156)
                .attr('height', d => positionedNodes.get(d.data.id)?.height ?? Math.max(48, 26 + (d.data.lines?.length || 1) * 16))
                .attr('rx', 8)
                .attr('ry', 8)
                .attr('fill', d => d.data.lifeStatus === 'deceased' ? '#e5e7eb' : '#ffffff')
                .attr('stroke', d => d.data.lifeStatus === 'deceased' ? '#9ca3af' : '#2f855a')
                .attr('stroke-width', 1.8)
                .style('filter', 'drop-shadow(0 3px 6px rgba(0,0,0,0.18))');

            nodeGroup.each(function(d) {
                const lines = d.data.lines || [''];
                const nodeHeight = positionedNodes.get(d.data.id)?.height ?? 48;
                const lineHeight = 15;
                const startY = -((lines.length - 1) * lineHeight) / 2;

                d3.select(this)
                    .append('circle')
                    .attr('cx', -((positionedNodes.get(d.data.id)?.width ?? 156) / 2) + 14)
                    .attr('cy', -((positionedNodes.get(d.data.id)?.height ?? 48) / 2) + 12)
                    .attr('r', 10)
                    .attr('fill', d.data.genderColor || '#6b7280')
                    .attr('stroke', '#ffffff')
                    .attr('stroke-width', 1.5);

                d3.select(this)
                    .append('text')
                    .attr('x', -((positionedNodes.get(d.data.id)?.width ?? 156) / 2) + 14)
                    .attr('y', -((positionedNodes.get(d.data.id)?.height ?? 48) / 2) + 16)
                    .attr('text-anchor', 'middle')
                    .attr('fill', '#ffffff')
                    .style('font-size', '15px')
                    .style('font-weight', '700')
                    .style('font-family', 'Arial, sans-serif')
                    .text(d.data.genderIcon || '•');

                const text = d3.select(this)
                    .append('text')
                    .attr('text-anchor', 'middle')
                    .attr('fill', darkColor)
                    .style('font-size', '11px')
                    .style('font-family', 'Arial, sans-serif')
                    .attr('y', Math.max(-nodeHeight / 2 + 16, startY));

                lines.forEach((line, index) => {
                    text.append('tspan')
                        .attr('x', 0)
                        .attr('dy', index === 0 ? 0 : 15)
                        .text(line);
                });
            });

            let isLayoutDirty = false;

            const markLayoutDirty = () => {
                isLayoutDirty = true;
                if (saveStatus) {
                    saveStatus.textContent = 'Có thay đổi chưa lưu';
                    saveStatus.style.color = '#b45309';
                }
            };

            const saveLayout = async () => {
                if (!saveButton) {
                    return;
                }

                const payload = {
                    nodes: Array.from(positionedNodes.entries()).map(([id, node]) => ({
                        id: Number(id),
                        x: Number(node.x),
                        y: Number(node.y),
                    })),
                };

                saveButton.disabled = true;
                if (saveStatus) {
                    saveStatus.textContent = 'Đang lưu...';
                    saveStatus.style.color = 'var(--gray)';
                }

                try {
                    const response = await fetch(saveLayoutUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!response.ok) {
                        throw new Error('Không thể lưu');
                    }

                    isLayoutDirty = false;
                    if (saveStatus) {
                        saveStatus.textContent = 'Đã lưu vị trí';
                        saveStatus.style.color = '#15803d';
                    }
                } catch (error) {
                    if (saveStatus) {
                        saveStatus.textContent = 'Lưu thất bại, thử lại';
                        saveStatus.style.color = '#b91c1c';
                    }
                } finally {
                    saveButton.disabled = false;
                }
            };

            const resetLayout = async () => {
                if (!resetButton) {
                    return;
                }

                const shouldReset = window.confirm('Reset bố cục về mặc định? Vị trí đã lưu sẽ bị xóa.');
                if (!shouldReset) {
                    return;
                }

                resetButton.disabled = true;
                if (saveButton) {
                    saveButton.disabled = true;
                }

                if (saveStatus) {
                    saveStatus.textContent = 'Đang reset...';
                    saveStatus.style.color = 'var(--gray)';
                }

                try {
                    const response = await fetch(resetLayoutUrl, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Không thể reset');
                    }

                    window.location.reload();
                } catch (error) {
                    if (saveStatus) {
                        saveStatus.textContent = 'Reset thất bại, thử lại';
                        saveStatus.style.color = '#b91c1c';
                    }

                    resetButton.disabled = false;
                    if (saveButton) {
                        saveButton.disabled = false;
                    }
                }
            };

            if (saveButton) {
                saveButton.addEventListener('click', saveLayout);
            }

            if (resetButton) {
                resetButton.addEventListener('click', resetLayout);
            }

            if (saveStatus) {
                saveStatus.textContent = savedPositionMap.size > 0 ? 'Đã nạp vị trí đã lưu' : 'Chưa có vị trí đã lưu';
            }

            nodeGroup.call(
                d3.drag()
                    .on('start', function() {
                        d3.select(this).raise();
                    })
                    .on('drag', function(event, d) {
                        const current = positionedNodes.get(d.data.id);
                        if (!current) {
                            return;
                        }

                        current.x += event.dx;
                        current.y += event.dy;
                        positionedNodes.set(d.data.id, current);

                        d3.select(this).attr('transform', `translate(${current.x},${current.y})`);
                        renderConnections();
                        markLayoutDirty();
                    })
            );
        })();
    </script>
@endsection
